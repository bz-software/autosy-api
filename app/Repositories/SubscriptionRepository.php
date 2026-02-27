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

    public function findByIdStripeSubscription($id, $idUser){
        return $this->model
            ->where('id_stripe_subscription', $id)
            ->where('id_user', $idUser)
            ->first();
    }

    public function existsAuthorized(int $idWorkshop): bool
    {
        return $this->model
            ->where('id_workshop', $idWorkshop)
            ->where('status', SubscriptionStatus::AUTHORIZED->value)
            ->exists();
    }
}