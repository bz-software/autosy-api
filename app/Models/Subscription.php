<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    protected $fillable = [
        'id_user',
        'id_subscription_plan',
        'status',
        'current_period_start',
        'current_period_end',
        'id_stripe_subscription',
        'cancel_at_period_end'
    ];

    protected $casts = [
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'next_billing_at' => 'datetime',
    ];

    protected $appends = [
        'days_left'
    ];

    public function getDaysLeftAttribute()
    {
        if (!$this->current_period_end) {
            return null;
        }

        $days = now()->diffInDays($this->current_period_end, false);

        return max((int) ceil($days), 0);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'id_subscription_plan', 'id');
    }
}