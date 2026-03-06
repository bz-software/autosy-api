<?php

namespace App\Repositories;

use App\Models\SubscriptionPlan;

class SubscriptionPlanRepository extends AbstractRepository
{
    public function __construct(SubscriptionPlan $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug): ?SubscriptionPlan
    {
        return $this->model
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }

    public function findByMercadoPagoPlanId(string $mercadoPagoPlanId): ?SubscriptionPlan
    {
        return $this->model
            ->where('mercado_pago_plan_id', $mercadoPagoPlanId)
            ->first();
    }
}