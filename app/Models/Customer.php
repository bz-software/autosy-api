<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'id_workshop'
    ];
    
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

    public function workshop()
    {
        return $this->belongsTo(Workshop::class, 'id_workshop');
    }
}
