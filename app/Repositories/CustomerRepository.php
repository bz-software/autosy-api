<?php
namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository
{
    public function __construct(private Customer $model) {}

    public function create($user){
        return $this->model->create($user);
    }
}

