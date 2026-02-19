<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class CashTransactionDTO extends AbstractDTO
{
    public function __construct(
        public int $id,
        public int $idWorkshop,
        public int $type,
        public int $category,
        public ?int $sourceType,
        public ?int $idAppointment,
        public ?int $idInventoryMovement,
        public float $amount,
        public int $paymentMethod,
        public string $transactionDate,
        public ?string $notes,
        public int $createdBy
    ) {}

    /**
     * Cria o DTO a partir do Request validado
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('id') ?? 0,
            $request->input('idWorkshop'),
            $request->input('type'),
            $request->input('category'),
            $request->input('sourceType'),
            $request->input('idAppointment'),
            $request->input('idInventoryMovement'),
            (float) $request->input('amount'),
            $request->input('paymentMethod'),
            $request->input('transactionDate'),
            $request->input('notes'),
            $request->user()->id
        );
    }
}
