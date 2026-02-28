<?php

namespace App\DTOs\Subscription;

use App\DTOs\AbstractDTO;
use App\Enums\SubscriptionStatus;
use Illuminate\Http\Request;

class SubscriptionDTO extends AbstractDTO
{
    public function __construct(
        public ?int $id,
        public int $id_user,
        public int $id_subscription_plan,
        public SubscriptionStatus $status,
        public ?string $current_period_start,
        public ?string $current_period_end,
        public ?string $created_at,
        public ?string $updated_at,
        public string $id_stripe_subscription
    ) {}

    
    /**
     * Cria o DTO a partir do Request validado
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['id_user'] ?? null,
            $data['id_subscription_plan'] ?? null,
            $data['status'] ?? null,
            $data['current_period_start'] ?? null,
            $data['current_period_end'] ?? null,
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null,
            $data['id_stripe_subscription'] ?? null
        );
    }
}
