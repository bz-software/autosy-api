<?php
namespace App\Services;

use App\DTOs\CustomerDTO;
use App\Exceptions\ServiceException;
use App\Repositories\ServiceRepository;

class ServiceService {
    public function __construct(
        private ServiceRepository $repository,
    ) {}

    public function list($idWorkshop){
        return $this->repository->all($idWorkshop);
    }
}

