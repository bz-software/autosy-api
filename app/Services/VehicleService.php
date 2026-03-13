<?php   
namespace App\Services;

use App\DTOs\VehicleDTO;
use App\Exceptions\ServiceException;
use App\Repositories\CustomerRepository;
use App\Repositories\VehicleRepository as Repository;
use Illuminate\Support\Facades\DB;

class VehicleService {
    public function __construct(
        private CustomerRepository $customerRepository,
        private Repository $repository,
    ) {}

    public function getByCustomer($idCustomer){
        $customer = $this->customerRepository->byId($idCustomer);

        $notFoundMessage = "Cliente não encontrado";
        if(empty($customer)){
            throw new ServiceException([], 404, $notFoundMessage);
        }

        return $this->repository->byCustomer($idCustomer);
    }

    public function search(VehicleDTO $params){
        return $this->repository->searchByParams($params);
    }

    public function store(VehicleDTO $vehicleDTO){
        $customer = $this->customerRepository->byId($vehicleDTO->id_customer);

        if(empty($customer)){
            throw new ServiceException([], 404, "Cliente não encontrado");
        }

        return DB::transaction(function () use ($vehicleDTO, $customer) {
            $vehicle = $this->repository->store($vehicleDTO->toArray());  

            if(!$vehicle){
                throw new ServiceException([], 500, "Falha ao salvar veículo");
            }

            return $vehicle;
        });
        
    }

    public function update($id, VehicleDTO $vehicleDTO){
        $vehicleDTO->id = $id;
        return $this->repository->update($id, $vehicleDTO->toArray());
    }
}

