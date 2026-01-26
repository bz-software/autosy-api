<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class VehicleDTO extends AbstractDTO
{
    public $id;
    public $id_customer;
    public $license_plate;
    public $model;

    /**
     * CustomerDTO constructor.
     * 
     * @param int $id
     * @param int $idCustomer
     * @param int $licensePlate
     * @param string $model
     */
    public function __construct($id, $idCustomer, $licensePlate, $model)
    {
        $this->id = $id;
        $this->id_customer = $idCustomer;
        $this->license_plate = $licensePlate;
        $this->model = $model;
    }

    /**
     * Método para criar o DTO a partir de uma requisição.
     * 
     * @param Request $request
     * @return VehicleDTO
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('id') ?? null,
            $request->input('idCustomer') ?? null,
            $request->input('licensePlate') ?? null,
            $request->input('model') ?? null
        );
    }
}
