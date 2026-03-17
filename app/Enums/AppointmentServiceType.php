<?php

namespace App\Enums;

use App\Enums\Traits\HasArrayRepresentation;

enum AppointmentServiceType: int
{
    use HasArrayRepresentation;

    case PECA = 1;
    case SERVICO = 2;

    public function label(): string
    {
        return match ($this) {
            self::PECA => 'Peça',
            self::SERVICO => 'Serviço',
        };
    }
}
