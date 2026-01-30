<?php
namespace App\Services;

use App\DTOs\AppointmentDTO;
use App\DTOs\AppointmentServiceDTO;
use App\Enums\AppointmentStatus;
use App\Enums\WorkshopType;
use App\Exceptions\ServiceException;
use App\Repositories\AppointmentRepository;
use App\Repositories\AppointmentServiceRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkshopRepository;

class AppointmentServiceService {
    public function __construct(
        private AppointmentServiceRepository $repository,
        private AppointmentRepository $rAppointment
    ) {}

    public function store($idAppointment, $idWorkshop, AppointmentServiceDTO $appointmentServiceDTO){
        $appointment = $this->rAppointment->one($idAppointment, $idWorkshop);

        if(empty($appointment)){
            throw new ServiceException([], 404, "Agendamento não encontrado");
        }

        if($appointment->status != AppointmentStatus::DIAGNOSTICO->value){
            throw new ServiceException([], 400, "Não é possível adicionar novos serviços.");
        }

        $appointmentServiceDTO->id_appointment = $idAppointment;

        $appointmentService = $this->repository->store($appointmentServiceDTO->toArray());
        if(!$appointmentService){
            throw new ServiceException([], 500, "Falha ao adicionar serviço");
        }

        return $appointmentService;
    }
}

