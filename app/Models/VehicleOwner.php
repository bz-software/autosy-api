<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleOwner extends Model
{
    protected $table = 'vehicle_owners';

    protected $fillable = [
        'id_vehicle',
        'id_customer',
        'start_date',
        'end_date',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'id_vehicle');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }
}