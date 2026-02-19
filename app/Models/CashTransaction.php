<?php

namespace App\Models;

use App\Enums\CashTransactionCategory;
use App\Enums\CashTransactionSourceType;
use App\Enums\CashTransactionType;
use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'cash_transactions';

    /*
     |--------------------------------------------------------------------------
     | Mass Assignment
     |--------------------------------------------------------------------------
     */

    protected $fillable = [
        'id_workshop',
        'type',
        'category',
        'source_type',
        'id_appointment',
        'id_inventory_movement',
        'amount',
        'payment_method',
        'transaction_date',
        'notes',
        'created_by',
    ];

    /*
     |--------------------------------------------------------------------------
     | Casts
     |--------------------------------------------------------------------------
     */

    protected $casts = [
        'id_workshop' => 'integer',
        'type' => CashTransactionType::class,
        'category' => CashTransactionCategory::class,
        'source_type' => CashTransactionSourceType::class,
        'id_appointment' => 'integer',
        'id_inventory_movement' => 'integer',
        'payment_method' => PaymentMethod::class,
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     */

    public function workshop()
    {
        return $this->belongsTo(Workshop::class, 'id_workshop');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'id_appointment');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*
     |--------------------------------------------------------------------------
     | Scopes (Vai usar MUITO em relatório)
     |--------------------------------------------------------------------------
     */

    public function scopeFromWorkshop(Builder $query, int $workshopId): Builder
    {
        return $query->where('id_workshop', $workshopId);
    }

    public function scopeIncome(Builder $query): Builder
    {
        return $query->where('type', CashTransactionType::INCOME->value);
    }

    public function scopeExpense(Builder $query): Builder
    {
        return $query->where('type', CashTransactionType::EXPENSE->value);
    }

    public function scopeBetweenDates(
        Builder $query,
        string $startDate,
        string $endDate
    ): Builder {
        return $query->whereBetween('transaction_date', [
            $startDate,
            $endDate
        ]);
    }

    /*
     |--------------------------------------------------------------------------
     | Helpers
     |--------------------------------------------------------------------------
     */

    public function isIncome(): bool
    {
        return $this->type === CashTransactionType::INCOME->value;
    }

    public function isExpense(): bool
    {
        return $this->type === CashTransactionType::EXPENSE->value;
    }
}
