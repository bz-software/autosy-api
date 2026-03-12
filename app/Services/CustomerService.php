<?php
namespace App\Services;

use App\DTOs\CustomerDTO;
use App\DTOs\WorkshopCustomer\WorkshopCustomerDTO;
use App\Exceptions\ServiceException;
use App\Repositories\CustomerRepository;
use App\Repositories\WorkshopCustomerRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerService {
    public function __construct(
        private CustomerRepository $repository,
        private WorkshopCustomerRepository $rWorkshopCustomer
    ) {}

    public function store(CustomerDTO $customerDto, $idWorkshop){
        $customerDto->id_workshop = $idWorkshop;

        $customer = $this->repository->create($customerDto->toArray());

        if(!$customer){
            throw new ServiceException([], 500, "Falha ao salvar cliente");
        }

        return $customer;
    }

    public function search(CustomerDTO $params){
        return $this->repository->searchByParams($params);
    }

    public function searchByWorkshop(CustomerDTO $params, $idWorkshop){
        return $this->repository->searchInWorkshopByParams($params, $idWorkshop);
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

