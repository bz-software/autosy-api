<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'id_workshop'
    ];

    public function scopeFromWorkshop($query, int $idWorkshop)
    {
        return $query->where('id_workshop', $idWorkshop);
    }

    protected function name(): Attribute
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
