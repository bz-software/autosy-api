<?php

namespace App\Enums;

use App\Enums\Traits\HasArrayRepresentation;

enum CashTransactionType: int
{
    use HasArrayRepresentation;

    case INCOME = 1;
    case EXPENSE = 2;

    public function label(): string
    {
        return match ($this) {
            self::INCOME => 'Entrada',
            self::EXPENSE => 'Saída',
        };
    }
}
