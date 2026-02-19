<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'id_user',
        'type'
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

    /**
     * =============
     * Relations
     * =============
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id_workshop');
    }
    
    public function customers()
    {
        return $this->hasMany(Customer::class, 'id_workshop');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'id_workshop');
    }

    public function cashTransactions()
    {
        return $this->hasMany(CashTransaction::class, 'id_workshop');
    }
}
