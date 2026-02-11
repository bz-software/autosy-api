<?php
namespace App\Services;

use App\DTOs\AppointmentDTO;
use App\DTOs\AppointmentServiceDTO;
use App\DTOs\AppointmentWithServicesDTO;
use App\Enums\AppointmentStatus;
use App\Enums\WorkshopType;
use App\Exceptions\ServiceException;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\StoreAppointmentWithServicesRequest;
use App\Repositories\AppointmentRepository;
use App\Repositories\AppointmentServiceRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\VehicleRepository;
use App\Repositories\WorkshopRepository;
use App\Validation\ValidationErrorFormatter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentService {
    public function __construct(
        private AppointmentRepository $repository,
        private AppointmentServiceRepository $rAppointmentService,
        private WorkshopRepository $rWorkshop,
        private VehicleRepository $rVehicle,
        private CustomerRepository $rCustomer,
        private ServiceRepository $rService
    ) {}

    public function list($idWorkshop){
        return $this->repository->allWithDetails($idWorkshop);
    }

    public function store($idWorkshop, Request $request){
        $workshop = $this->rWorkshop->one($idWorkshop);

        return match ($workshop->type) {
            WorkshopType::MECHANIC->value =>
                $this->storeMechanic($idWorkshop, $request),

            WorkshopType::DETAILING->value,
            WorkshopType::CAR_WASH->value =>
                $this->storeWithServices($idWorkshop, $request),
        };
    }

    public function storeMechanic($idWorkshop, Request $request){
        $this->validateWithFormRequest($request, new StoreAppointmentRequest());

        $dto = AppointmentDTO::fromRequest($request);
        
        if(empty($this->rCustomer->byId($dto->id_customer, $idWorkshop))){
            throw new ServiceException(['idCustomer' => "Cliente não encontrado"]);
        }

        if(empty($this->rVehicle->byIdAndCustomer($dto->id_vehicle, $dto->id_customer))){
            throw new ServiceException(['idVehicle' => "Veículo não encontrado"]);
        }

        $dto->id_workshop = $idWorkshop;
        $dto->status = (int) AppointmentStatus::AGENDADO->value;

        $appointment = $this->repository->store($dto->toArray());

        if(!$appointment->id){
            throw new ServiceException([], 500, "Falha ao agendar");
        }

        return $this->repository->withDetails($appointment->id);
    }

    public function storeWithServices($idWorkshop, Request $request){
        $this->validateWithFormRequest($request, new StoreAppointmentWithServicesRequest());

        $dto = AppointmentWithServicesDTO::fromRequest($request);

        if(empty($this->rCustomer->byId($dto->id_customer, $idWorkshop))){
            throw new ServiceException(['idCustomer' => "Cliente não encontrado"]);
        }

        if(empty($this->rVehicle->byIdAndCustomer($dto->id_vehicle, $dto->id_customer))){
            throw new ServiceException(['idVehicle' => "Veículo não encontrado"]);
        }

        $availableServices = $this->rService->all($idWorkshop);

        $invalidServiceIds = collect($dto->services)
            ->pluck('id_service')
            ->diff($availableServices->pluck('id'));

        if ($invalidServiceIds->isNotEmpty()) {
            throw new ServiceException([], 400,
                'Um ou mais serviços não pertencem a este estabelecimento'
            );
        }

        return DB::transaction(function () use ($dto, $idWorkshop) {
            $dto->id_workshop = $idWorkshop;
            $dto->status = (int) AppointmentStatus::ANDAMENTO->value;

            $appointment = $this->repository->store($dto->toArray());

            if(!$appointment->id){
                throw new ServiceException([], 500, "Falha ao agendar");
            }

            foreach($dto->services as $appointmentService){
                $appointmentService->id_appointment = $appointment->id;
                
                if(!$this->rAppointmentService->store($appointmentService->toArray())){
                    throw new ServiceException([], 500, "Falha ao agendar (item)");
                }
            }

            return $this->repository->withDetails($appointment->id);
        });
    }

    public function validateWithFormRequest(Request $request, $FormRequest){
        $validator = Validator::make(
            $request->all(),
            $FormRequest->rules(),
            $FormRequest->messages(),
            $FormRequest->attributes()
        );

        if ($validator->fails()) {
            throw new ServiceException(
                ValidationErrorFormatter::format($validator),
                400
            );
        }
    }

    public function startDiagnosis($id, $idWorkshop){
        $appointment = $this->repository->one($id, $idWorkshop);

        if(empty($appointment)){
            throw new ServiceException([], 400, "Agendamento não encontrado");
        }

        $workshop = $this->rWorkshop->one($idWorkshop);

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

        $workshop = $this->rWorkshop->one($idWorkshop);

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

        $workshop = $this->rWorkshop->one($idWorkshop);

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

    public function approval($id){
        $appointment = $this->repository->onePublic($id);

        if(empty($appointment)){
            throw new ServiceException([], 400, "Agendamento não encontrado");
        }

        $status = $appointment->status;

        if($status != AppointmentStatus::AGUARDANDO_APROVACAO->value){
            throw new ServiceException([], 400, "Orçamento indisponível");
        }

        return $this->repository->withDetails($id);
    }

    public function finalize($id, $idWorkshop){
        $appointment = $this->repository->one($id, $idWorkshop);

        if(empty($appointment)){
            throw new ServiceException([], 400, "Agendamento não encontrado");
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

