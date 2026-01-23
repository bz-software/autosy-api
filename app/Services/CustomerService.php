<?php
namespace App\Services;

use App\DTOs\CustomerDTO;
use App\Exceptions\ServiceException;
use App\Repositories\CustomerRepository;
use Illuminate\Support\Str;

class CustomerService {
    public function __construct(
        private CustomerRepository $repository,
    ) {}

    public function store(CustomerDTO $customerDto, $idWorkshop){
        $customerDto->name = Str::upper($customerDto->name);
        $customerDto->id_workshop = $idWorkshop;
        
        return $this->repository->create($customerDto->toArray());  
    }

    public function search(CustomerDTO $params){
        return $this->repository->searchByParams($params);
    }
}

