<?php

namespace App\Enums;

use App\Enums\Traits\HasArrayRepresentation;

enum CashTransactionSourceType: int
{
    use HasArrayRepresentation;

    case SERVICE = 1;
    case INVENTORY = 2;
    case MANUAL = 3;

    public function label(): string
    {
        return match ($this) {
            self::SERVICE => 'Serviço',
            self::INVENTORY => 'Estoque',
            self::MANUAL => 'Manual'
        };
    }
}
