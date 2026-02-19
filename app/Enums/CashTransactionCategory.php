<?php

namespace App\Enums;

use App\Enums\Traits\HasArrayRepresentation;

enum CashTransactionCategory: int
{
    use HasArrayRepresentation;

    /**
     * INCOME
     */
    case SERVICE = 1;
    case PRODUCT_SALE = 2;
    case INCOME_ADJUSTMENT = 3;
    case INCOME_OTHER = 4;

    /**
     * EXPENSE
     */
    case OPERATIONAL_EXPENSE = 5;
    case MATERIAL_PURCHASE = 6;
    case EXPENSE_ADJUSTMENT = 7;
    case EXPENSE_OTHER = 8;

    public function label(): string
    {
        return match ($this) {
            self::SERVICE => "Serviço",
            self::PRODUCT_SALE => "Venda de Produto",
            self::INCOME_ADJUSTMENT => "Ajuste",
            self::INCOME_OTHER => "Outros",

            self::OPERATIONAL_EXPENSE => "Conta do estabelecimento",
            self::MATERIAL_PURCHASE => "Compra de Material",
            self::EXPENSE_ADJUSTMENT => "Ajuste",
            self::EXPENSE_OTHER => "Outros"
        };
    }
}
