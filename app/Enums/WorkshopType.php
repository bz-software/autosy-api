<?php

namespace App\Enums;

use App\Enums\Traits\HasArrayRepresentation;

enum WorkshopType: int
{
    use HasArrayRepresentation;

    case MECHANIC = 1;
    case CAR_WASH = 2;
    case DETAILING = 3;

    public function label(): string
    {
        return match ($this) {
            self::MECHANIC => 'Oficina Mecânica',
            self::CAR_WASH => 'Lava-jato',
            self::DETAILING => 'Estética Automotiva',
        };
    }
}
