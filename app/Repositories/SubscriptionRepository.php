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

    public function findByIdStripeSubscription($id){
        return $this->model
            ->where('id_stripe_subscription', $id)
            ->first();
    }

    public function existsAuthorized(int $idWorkshop): bool
    {
        return $this->model
            ->where('id_workshop', $idWorkshop)
            ->where('status', SubscriptionStatus::AUTHORIZED->value)
            ->exists();
    }

    public function findByUser(int $idUser) {
        return $this->model->with('plan')
            ->where('id_user', $idUser)
            ->first();
    }
}