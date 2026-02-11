<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'id_workshop',
        'duration',
        'deleted'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    public function scopeFromWorkshop($query, int $idWorkshop)
    {
        return $query->where('id_workshop', $idWorkshop);
    }

    /**
     * =============
     * Relations
     * =============
     */
    public function workshop()
    {
        return $this->belongsTo(Workshop::class, 'id_workshop');
    }

    public function appointmentService(){
        return $this->hasOne(AppointmentService::class, 'id_service');
    }
}
