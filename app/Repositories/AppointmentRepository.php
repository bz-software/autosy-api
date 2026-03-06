<?php
namespace App\Repositories;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class AppointmentRepository
{
    public function __construct(
        private Appointment $model
    ) {}

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

    public function byDateDashboard($date, $idWorkshop, $limit = 5){
        return $this->model::with([
            'customer',
            'vehicle',
        ])
        ->fromWorkshop($idWorkshop)
        ->where('appointment_date', '=', $date) 
        ->limit($limit)
        ->orderByDesc('id')
        ->get();
    }

    public function countInProgress($idWorkshop){
        return $this->model
            ->fromWorkshop($idWorkshop)
            ->where('status', '!=', AppointmentStatus::FINALIZADO->value)
            ->count();
    }

    public function countCompletedByDate($idWorkshop, $start, $end){
        return $this->model
            ->fromWorkshop($idWorkshop)
            ->where('status', '=', AppointmentStatus::FINALIZADO->value)
            ->whereBetween('appointment_date', [$start, $end])
            ->count();
    }

    public function byPaymentMethodPeriod($idWorkshop, $start, $end){
        return $this->model::query()
            ->from('appointments as a')
            ->join('cash_transactions as ct', 'ct.id_appointment', '=', 'a.id')
            ->whereBetween('a.appointment_date', [$start, $end])
            ->where('a.status', AppointmentStatus::FINALIZADO->value)
            ->where('a.id_workshop', $idWorkshop)
            ->groupBy('ct.payment_method')
            ->select(
                'ct.payment_method',
                DB::raw('COUNT(a.id) as total')
            )
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

