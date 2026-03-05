<?php

namespace App\Services;

use App\DTOs\Subscription\SubscriptionDTO;
use App\Enums\SubscriptionStatus;
use App\Exceptions\ServiceException;
use App\Http\Resources\Subscription\SubscriptionResource;
use App\Repositories\SubscriptionRepository;
use App\Repositories\SubscriptionPlanRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Checkout\Session;
use Stripe\BillingPortal\Session as PortalSession;

class SubscribeWorkshopService
{
    public function __construct(
        private SubscriptionRepository $rSubscription,
        private SubscriptionPlanRepository $rPlanRepository,
        private UserRepository $rUser
    ) {}

    public function getCurrent($idUser) {
        $user = $this->rUser->findById($idUser);
        return self::resolveUserSubscription($user);
    }

    public function cancel($idUser) {
        $subscription = $this->rSubscription->findByUser($idUser);

        if(empty($subscription)){
            throw new ServiceException([], 400, "Assinatura não encontrada");
        }
        
        $stripe = new \Stripe\StripeClient(config('services.stripe.token'));

        try {
            $stripe->subscriptions->cancel($subscription->id_stripe_subscription, []);

            return response()->noContent(200);
        } catch (\Throwable $th) {
            throw new ServiceException([], 400, $th->getMessage());
        }
    }

    public function createCustomerPortal($idUser) {
        Stripe::setApiKey(config('services.stripe.token'));

        $user = $this->rUser->findById($idUser);

        if (!$user->id_customer_stripe) {
            throw new ServiceException([], 400, "Customer Stripe não encontrado");
        }

        $frontUrl = config('services.frontend.url');

        $session = PortalSession::create([
            'customer' => $user->id_customer_stripe,
            'return_url' => $frontUrl . '/conta'
        ]);

        return response()->json([
            'url' => $session->url
        ]);
    }

    public function createCheckoutSession($idUser, $idWorkshop){
        Stripe::setApiKey(config('services.stripe.token'));

        $idUserStripe = $this->createCustomerIfNotExists($idUser, $idWorkshop);

        $session = Session::create([
            'mode' => 'subscription',
            'customer' => $idUserStripe,
            'payment_method_types' => ['card', 'boleto'], // pix vem automatico com o boleto (pix é liberado após 60 dias de movimentação da conta stripe)
            'ui_mode' => 'embedded',
            'line_items' => [
                [
                    'price' => 'price_1T41VF3fdGANyAFQZCqjemm8', // passar pra tabela plans
                    'quantity' => 1,
                ],
            ],
            'return_url' => config('services.frontend.url') . '/pagamento/retorno?session_id={CHECKOUT_SESSION_ID}',
            'locale' => 'pt-BR',
        ]);

        return response()->json([
            'clientSecret' => $session->client_secret
        ]);
    }

    public static function resolveUserSubscription($user){
        if (!empty($user->subscription)) {
            return new SubscriptionResource($user->subscription);
        }

        $trialDays = config('services.autosy.trial_days');
        $ends = $user->created_at->copy()->addDays($trialDays);

        $daysLeft = now()->diffInDays($ends, false);

        return (object) [
            'trial' => true,
            'periodStart' => $user->created_at->format('Y-m-d H:i:s'),
            'periodEnd' => $ends->format('Y-m-d H:i:s'),
            'daysLeft' => max((int) ceil($daysLeft), 0)
        ];
    }

    public function getCheckoutSession($idUser, $idWorkshop, $token){
        Stripe::setApiKey(config('services.stripe.token'));

        $session = \Stripe\Checkout\Session::retrieve($token);

        return response()->json([
            'status' => $session->status,
            'payment_status' => $session->payment_status,
        ]);
    }

    private function createCustomerIfNotExists($idUser){
        Stripe::setApiKey(config('services.stripe.token'));

        $user = $this->rUser->findById($idUser);

        if (!empty($user->id_customer_stripe)) {
            return $user->id_customer_stripe;
        }

        $customerStripe = Customer::create([
            'phone' => $user->phone_number,
            'name' => $user->name,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

        $user->id_customer_stripe = $customerStripe->id;

        $this->rUser->update($user->id, $user->toArray());

        return $customerStripe->id;
    }

    public function stripeWebhook(Request $request)
    {
        try {
            $payload = $request->getContent();
            $signature = $request->header('Stripe-Signature');
            $secret = config('services.stripe.webhook_secret');

            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $signature,
                $secret
            );
        } catch (\Exception $e) {
            Log::error('Stripe Webhook Error: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        try {
            switch ($event->type) {
                case 'invoice.paid':
                    $this->handleInvoicePaid($event->data->object);
                    break;

                case 'invoice.payment_failed':
                    $this->handlePaymentFailed($event->data->object);
                    break;

                case "customer.subscription.created":
                    $this->handleSubscriptionCreated($event->data->object);
                    break;

                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event->data->object);
                    break;

                case "customer.subscription.updated":
                    $this->handleSubscriptionUpdated($event->data->object);
                    break;
            }
        } catch (\Throwable $th) {
            Log::error('Stripe Webhook Error: ' . $th->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }   

        return response()->json(['received' => true]);
    }

    private function handleSubscriptionCreated($invoice){
        $idStripeCustomer = $invoice->customer;
        $idStripeSubscription = $invoice->id;

        $user = $this->rUser->findByStripeCustomerId($idStripeCustomer);

        if (!$user) {
            throw new ServiceException([], 500, "User not found");
        };

        $plan = $this->rPlanRepository->findBySlug('plano_basico');

        $subscription = $this->rSubscription->findByUser($user->id);

        if(empty($subscription)){  
            $subscription = new SubscriptionDTO(
                null,
                $user->id,
                $plan->id,
                SubscriptionStatus::PENDING,
                null,
                null,
                null,
                null,
                $idStripeSubscription
            );
    
            return $this->rSubscription->create($subscription->toArray());
        }else{
            // TODO: Futuramente criar um novo ao invés de atualizar.
            $subscription->id_subscription_plan = $plan->id;
            $subscription->current_period_start = null;
            $subscription->current_period_end = null;
            $subscription->status = SubscriptionStatus::PENDING;
            $subscription->id_stripe_subscription = $idStripeSubscription;

            return $this->rSubscription->update($subscription->id, $subscription->toArray());
        }

    }

    private function handleInvoicePaid($invoice)
    {
        $idStripeCustomer = $invoice->customer;
        $idStripeSubscription = $invoice->parent->subscription_details->subscription ?? null;

        if(empty($idStripeSubscription)){
            throw new ServiceException([], 400, "Subscription not found (payload)");
        }

        if($invoice->status != 'paid'){
            throw new ServiceException([], 400, "Subscription not paid");
        }

        $user = $this->rUser->findByStripeCustomerId($idStripeCustomer);

        if (!$user) return;

        $subscription = $this->rSubscription->findByIdStripeSubscription($idStripeSubscription);
        $plan = $this->rPlanRepository->findBySlug('plano_basico');

        if(empty($subscription)){
            throw new ServiceException([], 400, "Subscription not found");
        }

        $subscription->status = SubscriptionStatus::AUTHORIZED;

        $subscription->id_subscription_plan = $plan->id;

        if(empty($invoice->lines->data[0]->period->start)){
            throw new ServiceException([], 400, "Period start not found");
        }

        if(empty($invoice->lines->data[0]->period->end)){
            throw new ServiceException([], 400, "Period end not found");
        }

        $subscription->current_period_start = date(
            'Y-m-d H:i:s',
            $invoice->lines->data[0]->period->start
        );

        $subscription->current_period_end = date(
            'Y-m-d H:i:s',
            $invoice->lines->data[0]->period->end
        );

        return $this->rSubscription->update($subscription->id, $subscription->toArray());
    }

    private function handlePaymentFailed($invoice)
    {
        $idStripeSubscription = $invoice->subscription ?? null;

        if (empty($idStripeSubscription)) {
            Log::warning('Stripe: subscription not found in payment_failed payload');
            return;
        }

        $subscription = $this->rSubscription->findByIdStripeSubscription($idStripeSubscription);

        if (empty($subscription)) {
            Log::warning("Stripe: local subscription not found");
            return;
        }

        // Marca como em atraso
        $subscription->status = SubscriptionStatus::PAYMENT_FAILED->value;

        //salvar data da falha
        // $subscription->last_payment_failed_at = now();

        // salvar próxima tentativa
        // if (!empty($invoice->next_payment_attempt)) {
        //     $subscription->next_payment_attempt = date(
        //         'Y-m-d H:i:s',
        //         $invoice->next_payment_attempt
        //     );
        // }

        $this->rSubscription->update($subscription->id, $subscription->toArray());
        Log::info("Stripe: payment failed for subscription {$idStripeSubscription}");
    }

    public function handleSubscriptionUpdated($payload){
        $idStripeSubscription = $payload->id ?? null;

        if (empty($idStripeSubscription)) {
            throw new ServiceException([], 400, "Subscription not found (customer.subscription.updated)");
        }

        $subscription = $this->rSubscription->findByIdStripeSubscription($idStripeSubscription);

        if(empty($subscription)){
            throw new ServiceException([], 400, "Subscription not found (database)");
        }

        if($payload->status == 'past_due'){
            $subscription->status = SubscriptionStatus::PAYMENT_FAILED->value;
        }

        if($payload->status == 'active'){
            $subscription->status = SubscriptionStatus::AUTHORIZED->value;
        }

        if($payload->status == 'canceled'){
            $subscription->status = SubscriptionStatus::CANCELLED->value;
        }
        
        if($payload->status == 'unpaid'){
            $subscription->status = SubscriptionStatus::PAYMENT_FAILED->value;
        }

        return $this->rSubscription->update($subscription->id, $subscription->toArray());
    }

    private function handleSubscriptionDeleted($invoice)
    {
        $idStripeSubscription = $invoice->id;

        $subscription = $this->rSubscription->findByIdStripeSubscription($idStripeSubscription);

        if(empty($subscription)){
            throw new ServiceException([], 400, "Subscription not found (customer.subscription.deleted)");
        }

        if($invoice->status == 'canceled'){
            $subscription->status = SubscriptionStatus::CANCELLED->value;

            return $this->rSubscription->update($subscription->id, $subscription->toArray());
        }
    }
}