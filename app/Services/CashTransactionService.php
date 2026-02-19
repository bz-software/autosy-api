<?php

namespace App\Services;

use App\DTOs\CashTransactionDTO;
use App\Exceptions\ServiceException;
use App\Repositories\CashTransactionRepository;
use Carbon\Carbon;

class CashTransactionService
{
    public function __construct(private CashTransactionRepository $repository){}

    public function store(CashTransactionDTO $dto, $idUser, $idWorkshop){
        $transactionDate = Carbon::parse($dto->transaction_date);

        $startOfCurrentMonth = Carbon::now()->startOfMonth();

        if ($transactionDate->lt($startOfCurrentMonth)) {
            throw new ServiceException([], 400, "Não é permitido lançar movimentações para meses anteriores.");
        }

        $dto->created_by = $idUser;
        $dto->id_workshop = $idWorkshop;
        return $this->repository->create($dto->toArray());
    }
}
