<?php

namespace App\Services;

use App\Repositories\SubscriptionRepository;

class SubscriptionAccessService
{
    public function __construct(
        private SubscriptionRepository $subscriptionRepository
    ) {}

    public function canAccess(int $idWorkshop): bool
    {
        return $this->subscriptionRepository
            ->existsAuthorized($idWorkshop);
    }
}