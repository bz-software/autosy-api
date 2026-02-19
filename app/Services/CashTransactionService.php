<?php

namespace App\Services;

use App\DTOs\CashTransactionDTO;
use App\Repositories\CashTransactionRepository;

class CashTransactionService
{
    public function __construct(private CashTransactionRepository $repository){}

    public function store(CashTransactionDTO $dto, $idUser, $idWorkshop){
        $dto->created_by = $idUser;
        $dto->id_workshop = $idWorkshop;

        return $this->repository->create($dto->toArray());
    }
}
