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

    public function destroy($idAppointmentService, $idAppointment, $idWorkshop){
        $appointment = $this->rAppointment->one($idAppointment, $idWorkshop);
        $statusAllowed = [
            AppointmentStatus::DIAGNOSTICO->value,
            AppointmentStatus::AGUARDANDO_APROVACAO->value
        ];

        if(empty($appointment)){
            throw new ServiceException([], 404, "Agendamento não encontrado");
        }

        if(!in_array($appointment->status, $statusAllowed)){
            throw new ServiceException([], 400, "Exclusão não permitida");
        }

        $appointmentService = $this->repository->one($idAppointmentService);

        if(empty($appointmentService)){
            throw new ServiceException([], 404, "Serviço não encontrado");
        }

        if($appointmentService->id_appointment != $idAppointment){
            throw new ServiceException([], 400, "Exclusão não permitida");
        }

        if($appointmentService->delete()){
            return response()->noContent(200);
        }

        throw new ServiceException([], 500, "Falha ao excluir serviço");
    }
}

