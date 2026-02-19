<?php

namespace App\Enums;

use App\Enums\Traits\HasArrayRepresentation;

enum PaymentMethod: int
{
    use HasArrayRepresentation;

    case CASH = 1;
    case PIX = 2;
    case CREDIT_CARD = 3;
    case DEBIT_CARD = 4;
    case BANK_TRANSFER = 5;
    case BOLETO = 6;
    case OTHER = 7; 

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Dinheiro',
            self::PIX => 'Pix',
            self::CREDIT_CARD => 'Cartão de crédito',
            self::DEBIT_CARD => 'Cartão de débito',
            self::BANK_TRANSFER => 'Transferência bancária',
            self::BOLETO => 'Boleto',
            self::OTHER => 'Outro',
        };
    }
}
