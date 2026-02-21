<?php

namespace App\Services;

use App\Repositories\SubscriptionRepository;
use Illuminate\Support\Facades\Http;

class MercadoPagoWebhookService
{
    public function __construct(
        private SubscriptionRepository $subscriptionRepository
    ) {}

    public function handle(string $mercadoPagoSubscriptionId): void
    {
        // $response = Http::withToken(config('services.mercadopago.token'))
        //     ->get("https://api.mercadopago.com/preapproval/{$mercadoPagoSubscriptionId}");

        // if (!$response->successful()) {
        //     return;
        // }

        // $data = $response->json();

        // $this->subscriptionRepository->updateByMercadoPagoId(
        //     $mercadoPagoSubscriptionId,
        //     [
        //         'status' => $data['status'],
        //         'next_billing_at' => $data['next_payment_date'] ?? null,
        //     ]
        // );
    }
}