<?php   
namespace App\Services;

use App\DTOs\CustomerDTO;
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
        $customer = $this->customerRepository->byId($idCustomer);

        $notFoundMessage = "Cliente não encontrado";
        if(empty($customer)){
            throw new ServiceException([], 404, $notFoundMessage);
        }

        if($customer->id_workshop != $idWorkshop){
            throw new ServiceException([], 404, $notFoundMessage);
        }

        return $this->repository->byCustomer($idCustomer);
    }
}

