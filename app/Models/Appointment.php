<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'id_workshop',
        'id_customer',
        'id_vehicle',
        'license_plate',
        'status',
        'notes',
        'deleted',
    ];

    protected $casts = [
        'deleted' => 'boolean',
    ];

    public function scopeFromWorkshop($query, int $idWorkshop)
    {
        return $query->where('id_workshop', $idWorkshop);
    }

    public function services()
    {
        return $this->hasMany(AppointmentService::class, 'id_appointment');
    }

    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class, 'id_workshop');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'id_vehicle');
    }

    public function cashTransaction()
    {
        return $this->hasOne(CashTransaction::class, 'id_appointment');
    }
}
