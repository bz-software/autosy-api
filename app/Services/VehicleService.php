<?php   
namespace App\Services;

use App\DTOs\CustomerDTO;
use App\DTOs\VehicleDTO;
use App\Exceptions\ServiceException;
use App\Repositories\CustomerRepository;
use App\Repositories\VehicleRepository as Repository;
use Illuminate\Support\Str;

class VehicleService {
    public function __construct(
        private CustomerRepository $customerRepository,
        private Repository $repository
    ) {}

    public function getByCustomer($idCustomer, $idWorkshop){
        $customer = $this->customerRepository->byId($idCustomer, $idWorkshop);

        $notFoundMessage = "Cliente não encontrado";
        if(empty($customer)){
            throw new ServiceException([], 404, $notFoundMessage);
        }

        if($customer->id_workshop != $idWorkshop){
            throw new ServiceException([], 404, $notFoundMessage);
        }

        return $this->repository->byCustomer($idCustomer);
    }

    public function store(VehicleDTO $vehicleDTO, $idWorkshop){
        $customer = $this->customerRepository->byId($vehicleDTO->id_customer, $idWorkshop);

        if(empty($customer)){
            throw new ServiceException([], 404, "Cliente não encontrado");
        }
        
        return $this->repository->store($vehicleDTO->toArray());  
    }

    public function update($id, VehicleDTO $vehicleDTO){
        $vehicleDTO->id = $id;
        return $this->repository->update($id, $vehicleDTO->toArray());
    }
}

