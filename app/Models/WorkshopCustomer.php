<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkshopCustomer extends Model
{
    protected $table = 'workshop_customers';

    protected $fillable = [
        'id_workshop',
        'id_customer',
    ];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class, 'id_workshop');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }
}