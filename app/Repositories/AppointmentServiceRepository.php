<?php
namespace App\Repositories;

use App\Models\AppointmentService;

class AppointmentServiceRepository
{
    public function __construct(private AppointmentService $model) {}

    public function store($appointmentService){
        return $this->model->create($appointmentService);
    }

    public function one($id){
        return $this->model
            ->where('id', $id)
            ->first();
    }

    public function update($id, $appointmentService){
        $data = $this->model
            ->where('id', $id)
            ->firstOrFail();

        $data->update($appointmentService);

        return $data;
    }

    public function byAppointment($idAppointment){
        return $this->model
            ->where('id_appointment', $idAppointment)
        ->get();
    }

    public function amount($idAppointment){
        return $this->model::where('id_appointment', $idAppointment)
            ->sum('unit_price');
    }
}

