<?php
namespace App\Services;

use App\DTOs\CustomerDTO;
use App\DTOs\ServiceDTO;
use App\Exceptions\ServiceException;
use App\Repositories\ServiceRepository;

class ServiceService {
    public function __construct(
        private ServiceRepository $repository,
    ) {}

    public function list($idWorkshop){
        return $this->repository->all($idWorkshop);
    }

    public function store($idWorkshop, ServiceDTO $dto){
        $dto->id_workshop = $idWorkshop;
        return $this->repository->store($dto->toArray());
    }

    public function update($idWorkshop, $id, ServiceDTO $dto){
        $service = $this->repository->one($id, $idWorkshop);

        if(empty($service)){
            throw new ServiceException([], 404, "Serviço não encontrado");
        }

        $dto->id_workshop = $idWorkshop;

        return $this->repository->update($id, $dto->toArray());
    }

    public function destroy($idWorkshop, $id){
        $service = $this->repository->one($id, $idWorkshop);

        if(empty($service)){
            throw new ServiceException([], 404, "Serviço não encontrado");
        }

        if($this->repository->delete($id)){
            return response()->noContent(200);
        }

        throw new ServiceException([], 400, "Erro ao excluir serviço");
    }
}

