<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class CashTransactionDTO extends AbstractDTO
{
    public function __construct(
        public int $id,
        public ?int $id_workshop,
        public int $type,
        public int $category,
        public ?int $source_type,
        public ?int $id_appointment,
        public ?int $id_inventory_movement,
        public float $amount,
        public int $payment_method,
        public string $transaction_date,
        public ?string $notes,
        public int $created_by
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
            $request->input('idAppointment') ?? null,
            $request->input('idInventoryMovement') ?? null,
            (float) $request->input('amount'),
            $request->input('paymentMethod'),
            $request->input('transactionDate'),
            $request->input('notes') ?? null,
            $request->user()->id
        );
    }
}
