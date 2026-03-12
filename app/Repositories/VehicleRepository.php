<?php
namespace App\Repositories;

use App\DTOs\VehicleDTO;
use App\Models\Vehicle;

class VehicleRepository extends AbstractRepository
{
    public function __construct(Vehicle $model)
    {
        parent::__construct($model);
    }

    public function store($vehicle){
        return $this->model->create($vehicle);
    }

    public function byCustomer($idCustomer){
        return $this->model
        ->fromCustomerOwner($idCustomer)
        ->get();
    }

    public function searchByParams(VehicleDTO $params){
        $customers = $this->model::query()
            ->when($params->license_plate, function ($query) use ($params) {
                $query->where('license_plate', 'like', "%{$params->license_plate}%");
            })
        ->get();

        return $customers;
    }
}

