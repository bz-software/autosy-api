<?php
namespace App\Services;

use App\DTOs\AppointmentDTO;
use App\Enums\AppointmentStatus;
use App\Exceptions\ServiceException;
use App\Repositories\AppointmentRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkshopRepository;

class AppointmentService {
    public function __construct(
        private AppointmentRepository $repository,
    ) {}

    public function list($idWorkshop){
        return $this->repository->allWithDetails($idWorkshop);
    }

    public function store($idWorkshop, AppointmentDTO $appointmentDTO){
        $appointmentDTO->id_workshop = $idWorkshop;
        $appointmentDTO->status = (int) AppointmentStatus::AGENDADO->value;

        $appointment = $this->repository->store($appointmentDTO->toArray());

        if(!$appointment->id){
            throw new ServiceException([], 500, "Falha ao agendar");
        }

        return $this->repository->findWithDetails($appointment->id);
    }
}

