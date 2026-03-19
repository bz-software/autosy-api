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

    public function byCustomer($idCustomer, $idWorkshop){
        return $this->model::query()
            ->select('vehicles.*')
            ->distinct()
            ->join('appointments as a', 'a.id_vehicle', '=', 'vehicles.id')
            ->join('workshop_customers as wc', 'wc.id_customer', '=', 'a.id_customer')
            ->where('a.id_workshop', $idWorkshop)
            ->where('a.id_customer', $idCustomer)
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

