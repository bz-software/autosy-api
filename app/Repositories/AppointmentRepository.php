<?php
namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository
{
    public function __construct(private Appointment $model) {}

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

    public function onePublic($id){
        return $this->model
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

    public function withDetails(int $id) {
        return $this->model::with([
            'customer',
            'vehicle',
            'services',
            'workshop',
            'cashTransaction'
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

    public function byCustomer($idCustomer){
        return $this->model::with([
            'services'
        ])
        ->where('deleted', false)
        ->where('id_customer', $idCustomer)
        ->get();
    }
}

