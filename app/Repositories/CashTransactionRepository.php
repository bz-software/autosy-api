<?php

namespace App\Repositories;

use App\DTOs\CashTransaction\SearchCashTransactionDTO;
use App\Models\CashTransaction;

class CashTransactionRepository extends AbstractRepository
{
    public function __construct(CashTransaction $model)
    {
        parent::__construct($model);
    }

    public function byIdWorkshop(SearchCashTransactionDTO $filters, $idWorkshop){
        return $this->model
            ->fromWorkshop($idWorkshop)
            ->when(
                $filters->start_date && $filters->end_date,
                fn ($query) => $query->betweenDates(
                    $filters->start_date,
                    $filters->end_date
                )
            )
            ->get();
    }
}
