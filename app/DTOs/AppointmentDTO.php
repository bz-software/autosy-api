<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class AppointmentDTO extends AbstractDTO
{
    public function __construct(
        public int $id_workshop,
        public int $id_customer,
        public int $id_vehicle,
        public string $license_plate,
        public int $status,
        public string $notes,
        public string $appointment_date
    ) {}

    
    /**
     * Cria o DTO a partir do Request validado
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('idWorkshop') ?? 0,
            $request->input('idCustomer') ?? null,
            $request->input('idVehicle') ?? null,
            $request->input('licensePlate') ?? null,
            $request->input('status') ?? 0,
            $request->input('notes') ?? null,
            $request->input('date') ?? null
        );
    }
}
