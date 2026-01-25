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

    public function byId($id, $idWorkshop){
        return $this->model
            ->fromWorkshop($idWorkshop)
            ->where('id', $id)
            ->first();
    }

    public function toUpdate($id, $phoneNumber, $idWorkshop){
        return $this->model
            ->fromWorkshop($idWorkshop)
            ->where('phone_number', $phoneNumber)
            ->where('id', '!=', $id)
            ->first();
    }

    public function searchByParams(CustomerDTO $params, $idWorkshop){
        $customers = $this->model::query()
            ->fromWorkshop($idWorkshop)
            ->when($params->phone_number, function ($query) use ($params) {
                $query->where('phone_number', 'like', "%{$params->phone_number}%");
            })
        ->get();

        return $customers;
    }

    public function update($id, $customer){
        $data = $this->model
            ->where('id', $id)
            ->firstOrFail();

        $data->update($customer);

        return $data;
    }
}

