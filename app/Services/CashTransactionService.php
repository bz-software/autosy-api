<?php

namespace App\Services;

use App\DTOs\CashTransaction\SearchCashTransactionDTO;
use App\DTOs\CashTransaction\CashTransactionDTO;
use App\Enums\CashTransactionCategory;
use App\Enums\CashTransactionSourceType;
use App\Enums\CashTransactionType;
use App\Exceptions\ServiceException;
use App\Models\Appointment;
use App\Repositories\AppointmentServiceRepository;
use App\Repositories\CashTransactionRepository;
use Carbon\Carbon;

class CashTransactionService
{
    public function __construct(
        private CashTransactionRepository $repository,
        private AppointmentServiceRepository $rAppointmentService
    ){}

    public function list(SearchCashTransactionDTO $dto, $idWorkshop){
        return $this->repository->byIdWorkshop($dto, $idWorkshop);
    }

    public function store(CashTransactionDTO $dto, $idUser, $idWorkshop){
        $transactionDate = Carbon::parse($dto->transaction_date);

        $startOfCurrentMonth = Carbon::now()->startOfMonth();

        if ($transactionDate->lt($startOfCurrentMonth)) {
            throw new ServiceException([], 400, "Não é permitido lançar movimentações para meses anteriores.");
        }
        
        if(!empty($dto->id_appointment)){
            throw new ServiceException([], 400, "Não é permitido lançar movimentações para serviços de forma manual.");
        }

        $typeEnum = CashTransactionType::from($dto->type);
        $categoryEnum = CashTransactionCategory::from($dto->category);

        // validates whether the category belongs to the type.
        if ($categoryEnum->type() !== $typeEnum) {
            throw new ServiceException([], 400, 'A categoria selecionada não pertence ao tipo informado.');
        }

        $dto->created_by = $idUser;
        $dto->id_workshop = $idWorkshop;
        $dto->source_type = CashTransactionSourceType::MANUAL->value;
        return $this->repository->create($dto->toArray());
    }

    public function update(CashTransactionDTO $dto, $id, $idWorkshop){
        $cashTransaction = $this->repository->findByIdAndWorkshop($id, $idWorkshop);

        if(empty($cashTransaction)){
            throw new ServiceException([], 404, "Movimentação não encontrada");
        }

        if($cashTransaction->id_appointment){
            throw new ServiceException([], 400, "Movimentação não pode ser alterada");
        }

        $transactionDate = Carbon::parse($dto->transaction_date);

        $startOfCurrentMonth = Carbon::now()->startOfMonth();

        if ($transactionDate->lt($startOfCurrentMonth)) {
            throw new ServiceException([], 400, "Não é permitido alterar movimentações para meses anteriores.");
        }

        $typeEnum = CashTransactionType::from($dto->type);
        $categoryEnum = CashTransactionCategory::from($dto->category);

        // validates whether the category belongs to the type.
        if ($categoryEnum->type() !== $typeEnum) {
            throw new ServiceException([], 400, 'A categoria selecionada não pertence ao tipo informado.');
        }

        // overwriting if it has been changed.
        $dto->created_by = $cashTransaction->created_by;
        $dto->id_workshop = $cashTransaction->id_workshop;

        return $this->repository->update($id, $dto->toArray());
    }

    public function destroy($id, $idWorkshop){
        $cashTransaction = $this->repository->findByIdAndWorkshop($id, $idWorkshop);

        if(empty($cashTransaction)){
            throw new ServiceException([], 404, "Movimentação não encontrada");
        }

        if($cashTransaction->id_appointment){
            throw new ServiceException([], 400, "Movimentação não pode ser excluída");
        }

        $transactionDate = Carbon::parse($cashTransaction->transaction_date);

        $startOfCurrentMonth = Carbon::now()->startOfMonth();

        if ($transactionDate->lt($startOfCurrentMonth)) {
            throw new ServiceException([], 400, "Não é permitido excluir movimentações para meses anteriores.");
        }

        if($this->repository->delete($id)){
            return response()->noContent(200);
        }

        throw new ServiceException([], 400, "Erro ao excluir movimentação");
    }

    public function createCashTransactionByAppointment(Appointment $appointment, $paymentMethod, $idWorkshop, $idUser){
        $appointmentServices = $this->rAppointmentService->byAppointment($appointment->id);

        $amount = 0;
        foreach($appointmentServices as $appointmentService){
            $unit = $appointmentService->unit_price * $appointmentService->quantity;

            $amount += $unit;
        }

        $cashTransactionDTO = new CashTransactionDTO(
            null,
            $idWorkshop,
            CashTransactionType::INCOME->value,
            CashTransactionCategory::SERVICE->value,
            CashTransactionSourceType::SERVICE->value,
            $appointment->id,
            null,
            $amount,
            $paymentMethod,
            Carbon::now()->format('Y-m-d'),
            null,
            $idUser
        );

        return $this->repository->create($cashTransactionDTO->toArray());
    }
}
