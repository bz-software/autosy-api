<?php
namespace App\Repositories;

use App\Models\VehicleOwner;

class VehicleOwnerRepository extends AbstractRepository
{
    public function __construct(VehicleOwner $model)
    {
        parent::__construct($model);
    }
}

