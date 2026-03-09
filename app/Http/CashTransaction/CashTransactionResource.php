<?php

namespace App\Http\Resources\CashTransaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CashTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id ?? null,
            "idWorkshop" => $this->id_workshop ?? null,
            "type" => $this->type ?? null,
            "category" => $this->category ?? null,
            "sourceType" => $this->source_type ?? null,
            "idAppointment" => $this->id_appointment ?? null,
            "idInventoryMovement" => $this->id_inventory_movement ?? null,
            "amount" => $this->amount ?? null,
            "paymentMethod" => $this->payment_method ?? null,
            "transactionDate" => $this->transaction_date?->format('Y-m-d'),
            "notes" => $this->notes ?? null,
            "createdBy" => $this->created_by ?? null,
            "createdAt" => $this->created_at?->format('Y-m-d H:i:s'),
            "updatedAt" => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
