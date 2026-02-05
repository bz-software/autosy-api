<?php
namespace App\Services;

use App\DTOs\AppointmentDTO;
use App\Enums\AppointmentStatus;
use App\Enums\WorkshopType;
use App\Exceptions\ServiceException;
use App\Repositories\AppointmentRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkshopRepository;

class AppointmentService {
    public function __construct(
        private AppointmentRepository $repository,
        private WorkshopRepository $rWorkshopp
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

        return $this->repository->withDetails($appointment->id);
    }

    public function startDiagnosis($id, $idWorkshop){
        $appointment = $this->repository->one($id, $idWorkshop);

        if(empty($appointment)){
            throw new ServiceException([], 400, "Agendamento não encontrado");
        }

        $workshop = $this->rWorkshopp->one($idWorkshop);

        if($workshop->type != WorkshopType::MECHANIC->value){
            throw new ServiceException([], 400, "Status do agendamento não pode ser alterado");
        }

        $status = $appointment->status;

        if($status != AppointmentStatus::AGENDADO->value){
            throw new ServiceException([], 400, "Status do agendamento não pode ser alterado");
        }

        $appointment->status = AppointmentStatus::DIAGNOSTICO->value;

        if(!$this->repository->update($id, $appointment->toArray())){
            throw new ServiceException([], 400, "Falha ao atualizar status do agendamento");
        }

        return $this->repository->withDetails($id);
    }

    public function requestApproval($id, $idWorkshop){
        $appointment = $this->repository->one($id, $idWorkshop);

        if(empty($appointment)){
            throw new ServiceException([], 400, "Agendamento não encontrado");
        }

        $workshop = $this->rWorkshopp->one($idWorkshop);

        if($workshop->type != WorkshopType::MECHANIC->value){
            throw new ServiceException([], 400, "Status do agendamento não pode ser alterado");
        }

        $status = $appointment->status;

        if($status != AppointmentStatus::DIAGNOSTICO->value){
            throw new ServiceException([], 400, "Status do agendamento não pode ser alterado");
        }

        $appointment->status = AppointmentStatus::AGUARDANDO_APROVACAO->value;

        if(!$this->repository->update($id, $appointment->toArray())){
            throw new ServiceException([], 400, "Falha ao atualizar status do agendamento");
        }

        return $this->repository->withDetails($id);
    }

    public function approveDiagnosis($id, $idWorkshop){
        $appointment = $this->repository->one($id, $idWorkshop);

        if(empty($appointment)){
            throw new ServiceException([], 400, "Agendamento não encontrado");
        }

        $workshop = $this->rWorkshopp->one($idWorkshop);

        if($workshop->type != WorkshopType::MECHANIC->value){
            throw new ServiceException([], 400, "Status do agendamento não pode ser alterado");
        }

        $status = $appointment->status;

        if($status != AppointmentStatus::AGUARDANDO_APROVACAO->value){
            throw new ServiceException([], 400, "Status do agendamento não pode ser alterado");
        }

        $appointment->status = AppointmentStatus::ANDAMENTO->value;

        if(!$this->repository->update($id, $appointment->toArray())){
            throw new ServiceException([], 400, "Falha ao atualizar status do agendamento");
        }

        return $this->repository->withDetails($id);
    }

    public function finalize($id, $idWorkshop){
        $appointment = $this->repository->one($id, $idWorkshop);

        if(empty($appointment)){
            throw new ServiceException([], 400, "Agendamento não encontrado");
        }

        $workshop = $this->rWorkshopp->one($idWorkshop);

        if($workshop->type != WorkshopType::MECHANIC->value){
            throw new ServiceException([], 400, "Status do agendamento não pode ser alterado");
        }

        $status = $appointment->status;

        if($status != AppointmentStatus::ANDAMENTO->value){
            throw new ServiceException([], 400, "Status do agendamento não pode ser alterado");
        }

        $appointment->status = AppointmentStatus::FINALIZADO->value;

        if(!$this->repository->update($id, $appointment->toArray())){
            throw new ServiceException([], 400, "Falha ao atualizar status do agendamento");
        }

        return $this->repository->withDetails($id);
    }
}

