<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    protected $primaryKey = 'id_subscription';

    protected $fillable = [
        'id_workshop',
        'id_subscription_plan',
        'mercado_pago_subscription_id',
        'status',
        'current_period_start',
        'current_period_end',
        'next_billing_at',
        'external_reference',
    ];

    protected $casts = [
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'next_billing_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class, 'id_workshop', 'id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'id_subscription_plan', 'id_subscription_plan');
    }
}