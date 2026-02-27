<?php

namespace App\Services;

use App\Enums\SubscriptionStatus;
use App\Repositories\CustomerRepository;
use App\Repositories\SubscriptionRepository;
use App\Repositories\SubscriptionPlanRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Checkout\Session;
use Stripe\Webhook;

class SubscribeWorkshopService
{
    public function __construct(
        private SubscriptionRepository $rSubscription,
        private SubscriptionPlanRepository $rPlanRepository,
        private UserRepository $rUser
    ) {}

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

    public function getCheckoutSession($idUser, $idWorkshop, $token){
        Stripe::setApiKey(config('services.stripe.token'));

        $session = \Stripe\Checkout\Session::retrieve($token);

        return response()->json([
            'status' => $session->status,
            'payment_status' => $session->payment_status,
        ]);
    }
    
    // public function stripeWebhook(Request $request){
    //     $payload = $request->all();  

    //     try {
    //         $event = \Stripe\Event::constructFrom(
    //             $payload
    //         );
    //     } catch (\Throwable $th) {
    //         $th->getMessage();   
    //     }

    //     return response()->json(['received' => true]);
    // }

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
        $payload = $request->all();  

        try {
            $event = \Stripe\Event::constructFrom(
                $payload
            );
        } catch (\Exception $e) {
            Log::error('Stripe Webhook Error: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'invoice.paid':
                $this->handleInvoicePaid($event->data->object);
                break;

            case 'invoice.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;
        }

        return response()->json(['received' => true]);
    }

    private function handleInvoicePaid($invoice)
    {
        $idStripeCustomer = $invoice->customer;
        $idStripeSubscription = $invoice->subscription;

        $user = $this->rUser->findByStripeCustomerId($idStripeCustomer);

        if (!$user) return;

        $subscription = $this->rSubscription->findByIdStripeSubscription($idStripeSubscription, $user->id);
        $plan = $this->rPlanRepository->findBySlug('plano_basico');

        if(!empty($subscription)){
            $subscription->status = SubscriptionStatus::AUTHORIZED;

            $subscription->id_subscription_plan = $plan->id;

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
    }

    private function handlePaymentFailed($invoice)
    {
        $subscriptionId = $invoice->subscription;

        // Subscription::where('stripe_subscription_id', $subscriptionId)
        //     ->update(['status' => 'past_due']);
    }

    private function handleSubscriptionDeleted($subscription)
    {
        // Subscription::where('stripe_subscription_id', $subscription->id)
        //     ->update(['status' => 'canceled']);
    }
}