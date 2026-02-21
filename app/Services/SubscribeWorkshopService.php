<?php

namespace App\Services;

use App\Repositories\SubscriptionRepository;
use App\Repositories\SubscriptionPlanRepository;
use Illuminate\Support\Facades\Http;

class SubscribeWorkshopService
{
    public function __construct(
        private SubscriptionRepository $subscriptionRepository,
        private SubscriptionPlanRepository $planRepository
    ) {}

    public function execute(
        int $idWorkshop,
        string $planSlug,
        string $payerEmail,
        string $cardToken
    ): void {

        $plan = $this->planRepository->findBySlug($planSlug);

        if (!$plan) {
            throw new \Exception('Plano não encontrado.');
        }

        $response = Http::withToken(config('services.mercadopago.token'))
            ->post('https://api.mercadopago.com/preapproval', [
                'preapproval_plan_id' => $plan->mercado_pago_plan_id,
                'reason' => $plan->name,
                'external_reference' => 'autosy_workshop_' . $idWorkshop,
                'payer_email' => $payerEmail,
                'card_token_id' => $cardToken,
                'status' => 'authorized'
            ]);

        if (!$response->successful()) {
            throw new \Exception('Erro ao criar assinatura no Mercado Pago.');
        }

        $data = $response->json();

        $this->subscriptionRepository->create([
            'id_workshop' => $idWorkshop,
            'id_subscription_plan' => $plan->id_subscription_plan,
            'mercado_pago_subscription_id' => $data['id'],
            'status' => $data['status'] ?? 'authorized',
            'external_reference' => 'autosy_workshop_' . $idWorkshop,
        ]);
    }
}