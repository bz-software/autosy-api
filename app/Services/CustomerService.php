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

    public function search(CustomerDTO $params, $idWorkshop){
        return $this->repository->searchByParams($params, $idWorkshop);
    }

    public function update($id, $idWorkshop, CustomerDTO $customerDTO){
        $customer = $this->repository->byId($id, $idWorkshop);

        if(empty($customer)){
            throw new ServiceException([], 404, "Cliente não encontrado");
        }

        $customerDTO->id_workshop = $idWorkshop;
        return $this->repository->update($id, $customerDTO->toArray());
    }
}

