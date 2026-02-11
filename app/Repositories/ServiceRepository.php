<?php
namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\Service;
use App\Support\ServiceTemplate;

class ServiceRepository
{
    public function __construct(private Service $model) {}

    public function store($service){
        return $this->model->create($service);
    }

    public function one($id, $idWorkshop){
        return $this->model
            ->fromWorkshop($idWorkshop)
            ->where('id', $id)
            ->where('deleted', false)
            ->first();
    }

    public function all($idWorkshop){
        return $this->model
            ->fromWorkshop($idWorkshop)
            ->where('deleted', false)
            ->get();
    }

    public function update($id, $service){
        $data = $this->model
            ->where('id', $id)
            ->firstOrFail();

        $data->update($service);

        return $data;
    }

    public function delete($id){
        $data = $this->model
            ->where('id', $id)
            ->firstOrFail();

        $data->deleted = true;

        $updated = $data->save();

        return $updated;
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

