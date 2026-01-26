<?php
namespace App\Repositories;
use App\Models\Vehicle;

class VehicleRepository
{
    public function __construct(private Vehicle $model) {}

    public function store($vehicle){
        return $this->model->create($vehicle);
    }

    public function byCustomer($idCustomer){
        return $this->model->where('id_customer', $idCustomer)->get();
    }

    public function update($id, $vehicle){
        $data = $this->model
            ->where('id', $id)
            ->firstOrFail();

        $data->update($vehicle);

        return $data;
    }
}

