<?php

namespace App\DTOs\CashTransaction;

use App\DTOs\AbstractDTO;
use Illuminate\Http\Request;

class SearchCashTransactionDTO extends AbstractDTO
{
    public function __construct(
        public ?string $start_date,
        public ?string $end_date
    ) {}

    /**
     * Cria o DTO a partir do Request validado
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('start_date') ?? null,
            $request->input('end_date') ?? null
        );
    }
}
