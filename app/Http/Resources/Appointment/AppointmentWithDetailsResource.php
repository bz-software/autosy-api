<?php

namespace App\Http\Resources\Appointment;

use App\Http\Resources\AppointmentService\AppointmentServiceResource;
use App\Http\Resources\CashTransaction\CashTransactionResource;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Vehicle\VehicleResource;
use App\Http\Resources\Workshop\WorkshopResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentWithDetailsResource extends JsonResource
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
            'idWorkshop' => $this->id_workshop ?? null,
            'idCustomer' => $this->id_customer ?? null,
            'idVehicle' => $this->id_vehicle ?? null,
            'licensePlate' => $this->license_plate ?? null,
            'date' => $this->appointment_date,
            'status' => intval($this->status) ?? null,
            'notes' => $this->notes ?? null,
            'customer' => new CustomerResource($this->customer),
            'vehicle' => new VehicleResource($this->vehicle),
            'services' => AppointmentServiceResource::collection($this->services),
            'workshop' => new WorkshopResource($this->workshop),
            'cashTransaction' => new CashTransactionResource($this->cashTransaction)
        ];
    }
}
