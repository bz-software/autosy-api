<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => mb_strtoupper($value, 'UTF-8')
        );
    }
    
    public function scopeFromWorkshop($query, $idWorkshop)
    {
        return $query->join('workshop_customers', 'customers.id', '=', 'workshop_customers.id_customer')
            ->where('workshop_customers.id_workshop', $idWorkshop);
    }

    /**
     * =============
     * Relations
     * =============
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'id_customer');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'id_customer');
    }

    public function workshops(){
        return $this->belongsToMany(Workshop::class, 'workshop_customers', 'id_customer', 'id_workshop');
    }
}
