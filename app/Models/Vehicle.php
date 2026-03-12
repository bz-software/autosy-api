<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'license_plate',
        'model'
    ];

    protected function licensePlate(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                $plate = mb_strtoupper($value, 'UTF-8');

                return preg_replace('/[^A-Z0-9]/', '', $plate);
            }
        );
    }

    protected function model(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => mb_strtoupper($value, 'UTF-8')
        );
    }

    public function scopeFromCustomerOwner($query, $idCustomer)
    {
        return $query->join('vehicle_owners', 'vehicles.id', '=', 'vehicle_owners.id_vehicle')
            ->where('vehicle_owners.id_customer', $idCustomer)
            ->whereNull('vehicle_owners.end_date');
    }

    /**
     * =============
     * Relations
     * =============
     */
    // public function customer()
    // {
    //     return $this->belongsTo(Customer::class, 'id_customer');
    // }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'id_vehicle');
    }
}
