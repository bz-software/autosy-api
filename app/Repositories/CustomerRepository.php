<?php
namespace App\Repositories;

use App\DTOs\CustomerDTO;
use App\Models\Customer;

class CustomerRepository
{
    public function __construct(private Customer $model) {}

    public function create($user){
        return $this->model->create($user);
    }

    public function byId($id){
        return $this->model::find($id);
    }

    public function searchByParams(CustomerDTO $params){
        $customers = $this->model::query()
            ->when($params->phone_number, function ($query) use ($params) {
                $query->where('phone_number', 'like', "%{$params->phone_number}%");
            })
        ->get();

        return $customers;
    }
}

