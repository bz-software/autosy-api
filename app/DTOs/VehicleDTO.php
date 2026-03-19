<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class VehicleDTO extends AbstractDTO
{
    public $id;
    public $id_customer;
    public $license_plate;
    public $brand;
    public $model;
    public $year;
    public $engine;
    public $color;

    /**
     * VehicleDTO constructor.
     * 
     * @param int|null $id
     * @param int|null $idCustomer
     * @param string|null $licensePlate
     * @param string|null $brand
     * @param string|null $model
     * @param int|null $year
     * @param string|null $engine
     * @param string|null $color
     */
    public function __construct(
        $id,
        $idCustomer,
        $licensePlate,
        $brand,
        $model,
        $year,
        $engine,
        $color
    ) {
        $this->id = $id;
        $this->id_customer = $idCustomer;
        $this->license_plate = $licensePlate;
        $this->brand = $brand;
        $this->model = $model;
        $this->year = $year;
        $this->engine = $engine;
        $this->color = $color;
    }

    /**
     * Criar DTO a partir da request
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('id') ?? null,
            $request->input('idCustomer') ?? null,
            $request->input('licensePlate') ?? null,
            $request->input('brand') ?? null,
            $request->input('model') ?? null,
            $request->input('year') ?? null,
            $request->input('engine') ?? null,
            $request->input('color') ?? null
        );
    }
}