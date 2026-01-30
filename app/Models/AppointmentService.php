<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AppointmentService extends Model
{
    protected $table = 'appointment_services';

    protected $fillable = [
        'id_appointment',
        'service_name',
        'unit_price',
        'quantity',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    protected function serviceName(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => mb_strtoupper($value, 'UTF-8')
        );
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'id_appointment');
    }
}
