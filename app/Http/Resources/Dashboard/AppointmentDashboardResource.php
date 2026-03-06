<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Vehicle\VehicleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentDashboardResource extends JsonResource
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
            'date' => $this->appointment_date->format('Y-m-d'),
            'status' => intval($this->status) ?? null,
            'customer' => new CustomerResource($this->customer),
            'vehicle' => new VehicleResource($this->vehicle),
        ];
    }
}
