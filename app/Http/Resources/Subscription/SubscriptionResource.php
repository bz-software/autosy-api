<?php

namespace App\Http\Resources\Subscription;

use App\Http\Resources\SubscriptionPlan\SubscriptionPlanResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'status' => $this->status ?? null,
            'periodStart' => $this->current_period_start?->format('Y-m-d H:i:s') ?? null,
            'periodEnd' => $this->current_period_end?->format('Y-m-d H:i:s') ?? null,
            'plan' => new SubscriptionPlanResource($this->plan),
            'daysLeft' => $this->days_left,
            'cancelAtPeriodEnd' => boolval($this->cancel_at_period_end)
        ];
    }
}
