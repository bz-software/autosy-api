<?php

namespace App\Repositories;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;

class SubscriptionRepository extends AbstractRepository
{
    public function __construct(Subscription $model)
    {
        parent::__construct($model);
    }

    public function findActiveByWorkshop(int $idWorkshop): ?Subscription
    {
        return $this->model
            ->where('id_workshop', $idWorkshop)
            ->where('status', SubscriptionStatus::AUTHORIZED->value)
            ->first();
    }

    public function findByMercadoPagoId(string $mercadoPagoSubscriptionId): ?Subscription
    {
        return $this->model
            ->where('mercado_pago_subscription_id', $mercadoPagoSubscriptionId)
            ->first();
    }

    public function updateByMercadoPagoId(string $mercadoPagoSubscriptionId, array $data): bool
    {
        return $this->model
            ->where('mercado_pago_subscription_id', $mercadoPagoSubscriptionId)
            ->update($data);
    }

    public function existsAuthorized(int $idWorkshop): bool
    {
        return $this->model
            ->where('id_workshop', $idWorkshop)
            ->where('status', SubscriptionStatus::AUTHORIZED->value)
            ->exists();
    }
}