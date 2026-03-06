<?php

namespace App\Repositories;

use App\DTOs\CashTransaction\SearchCashTransactionDTO;
use App\Models\CashTransaction;
use Illuminate\Database\Eloquent\Model;

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
            ->orderByDate()
            ->get();
    }

    public function findByIdAndWorkshop(int $id, int $idWorkshop)
    {
        return $this->model
            ->fromWorkshop($idWorkshop)
            ->find($id);
    }
}
