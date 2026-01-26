<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'id_customer',
        'license_plate',
        'model'
    ];

    protected function licensePlate(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => mb_strtoupper($value, 'UTF-8')
        );
    }

    protected function model(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => mb_strtoupper($value, 'UTF-8')
        );
    }

    /**
     * =============
     * Relations
     * =============
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'id_vehicle');
    }
}
