<?php
namespace App\Repositories;

use App\Models\WorkshopCustomer;

class WorkshopCustomerRepository extends AbstractRepository
{
    public function __construct(WorkshopCustomer $model)
    {
        parent::__construct($model);
    }

    public function find($idCustomer, $idWorkshop){
        return $this->model
            ->where('id_customer', $idCustomer)
            ->where('id_workshop', $idWorkshop)
        ->first();
    }
}

