<?php

namespace App\Services;

use App\DTOs\CashTransaction\SearchCashTransactionDTO;
use App\DTOs\CashTransactionDTO;
use App\Enums\CashTransactionCategory;
use App\Enums\CashTransactionType;
use App\Exceptions\ServiceException;
use App\Repositories\CashTransactionRepository;
use Carbon\Carbon;

class CashTransactionService
{
    public function __construct(private CashTransactionRepository $repository){}

    public function list(SearchCashTransactionDTO $dto, $idWorkshop){
        return $this->repository->byIdWorkshop($dto, $idWorkshop);
    }

    public function store(CashTransactionDTO $dto, $idUser, $idWorkshop){
        $transactionDate = Carbon::parse($dto->transaction_date);

        $startOfCurrentMonth = Carbon::now()->startOfMonth();

        if ($transactionDate->lt($startOfCurrentMonth)) {
            throw new ServiceException([], 400, "Não é permitido lançar movimentações para meses anteriores.");
        }

        $typeEnum = CashTransactionType::from($dto->type);
        $categoryEnum = CashTransactionCategory::from($dto->category);

        // validates whether the category belongs to the type.
        if ($categoryEnum->type() !== $typeEnum) {
            throw new ServiceException([], 400, 'A categoria selecionada não pertence ao tipo informado.');
        }

        $dto->created_by = $idUser;
        $dto->id_workshop = $idWorkshop;
        return $this->repository->create($dto->toArray());
    }
}
