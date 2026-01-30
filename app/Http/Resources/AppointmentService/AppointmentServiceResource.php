<?php

namespace App\Http\Resources\AppointmentService;

use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Vehicle\VehicleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentServiceResource extends JsonResource
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
            'idAppointment' => $this->id_appointment ?? null,
            'serviceName' => $this->service_name ?? null,
            'unitPrice' => $this->unit_price ?? null,
            'quantity' => $this->quantity ?? null,
        ];
    }
}
