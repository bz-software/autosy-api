<?php
namespace App\Repositories;

use App\Models\Workshop;

class WorkshopRepository
{
    public function __construct(private Workshop $model) {}

    public function create($workshop){
        return $this->model->create($workshop);
    }

    public function one($id){
        return $this->model->find($id);
    }
}

