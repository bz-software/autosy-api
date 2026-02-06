<?php
namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\Service;
use App\Support\ServiceTemplate;

class ServiceRepository
{
    public function __construct(private Service $model) {}

    public function store($appointment){
        return $this->model->create($appointment);
    }

    public function one($id, $idWorkshop){
        return $this->model
            ->fromWorkshop($idWorkshop)
            ->where('id', $id)
            ->where('deleted', false)
            ->first();
    }

    public function update($id, $appointment){
        $data = $this->model
            ->where('id', $id)
            ->firstOrFail();

        $data->update($appointment);

        return $data;
    }

    public function createDefaultServices($idWorkshop, $workshopType){
        $templates = ServiceTemplate::byType($workshopType);

        if(empty($templates)){
            return true;
        }

        foreach ($templates as $template) {
            $data = [
                'id_workshop' => $idWorkshop,
                'name'             => $template['name'],
                'duration'         => $template['duration'],
            ];
            
            if(!$this->store($data)){
                throw new RepositoryException("Falha ao salvar estabelecimento", 500);
            }
        }

        return true;
    }


}

