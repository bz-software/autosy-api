<?php
namespace App\Repositories;

use App\Enums\AppointmentStatus;
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

    public function periodRevenue($idWorkshop, $startDate, $endDate){
        return $this->model::query()
            ->join('appointments as a', 'a.id', '=', 'appointment_services.id_appointment')
            ->whereBetween('a.appointment_date', [$startDate, $endDate])
            ->where('a.status', AppointmentStatus::FINALIZADO->value)
            ->where('a.id_workshop', $idWorkshop)
            ->selectRaw('SUM(appointment_services.unit_price * appointment_services.quantity) as total')
            ->value('total') ?? 0;
    }

    public function amount($idAppointment){
        return $this->model::where('id_appointment', $idAppointment)
            ->sum('unit_price');
    }
}

