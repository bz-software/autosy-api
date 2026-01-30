<?php
namespace App\Repositories;

use App\Models\AppointmentService;

class AppointmentServiceRepository
{
    public function __construct(private AppointmentService $model) {}

    public function store($appointment){
        return $this->model->create($appointment);
    }

    public function one($id, $idWorkshop){
        return $this->model
            ->fromWorkshop($idWorkshop)
            ->where('id', $id)
            ->where('deleted', false)
            ->first();
    }

    public function update($id, $appointment){
        $data = $this->model
            ->where('id', $id)
            ->firstOrFail();

        $data->update($appointment);

        return $data;
    }
}

