<?php
namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository
{
    public function __construct(private Appointment $model) {}

    public function store($appointment){
        return $this->model->create($appointment);
    }

    public function findWithDetails(int $id) {
        return $this->model::with([
            'customer',
            'vehicle',
        ])
        ->where('deleted', false)
        ->find($id);
    }

    public function allWithDetails($idWorkshop){
        return $this->model::with([
            'customer',
            'vehicle',
        ])
        ->where('deleted', false)
        ->where('id_workshop', $idWorkshop)
        ->get();
    }
}

