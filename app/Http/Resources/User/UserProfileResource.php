<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Workshop\WorkshopResource;

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
            "workshop" => new WorkshopResource($this->workshop) ?? null
        ];
    }
}
