<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Subscription\SubscriptionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Workshop\WorkshopResource;
use App\Services\SubscribeWorkshopService;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id ?? null,
            "name" => $this->name ?? null,
            "phoneNumber" => $this->phone_number ?? null,
            "workshop" => new WorkshopResource($this->workshop) ?? null,
            'subscription' => SubscribeWorkshopService::resolveUserSubscription($this) ?? null
        ];
    }
}
