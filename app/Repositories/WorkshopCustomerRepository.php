<?php
namespace App\Repositories;

use App\Models\WorkshopCustomer;

class WorkshopCustomerRepository extends AbstractRepository
{
    public function __construct(WorkshopCustomer $model)
    {
        parent::__construct($model);
    }
}

