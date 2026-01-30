<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class AppointmentServiceDTO extends AbstractDTO
{
    public function __construct(
        public int $id_appointment,
        public string $service_name,
        public float $unit_price,
        public int $quantity,
    ) {}

    /**
     * Cria o DTO a partir do Request validado
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('idAppointment') ?? 0,
            $request->input('serviceName') ?? null,
            $request->input('unitPrice') ?? 0,
            $request->input('quantity') ?? 0,
        );
    }
}
