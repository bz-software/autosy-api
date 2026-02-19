<?php

namespace App\Repositories;

use App\Models\CashTransaction;

class CashTransactionRepository extends AbstractRepository
{
    public function __construct(CashTransaction $model)
    {
        parent::__construct($model);
    }
}
