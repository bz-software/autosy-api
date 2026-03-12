<?php

namespace App\DTOs\VehicleOwner;

use App\DTOs\AbstractDTO;
use Illuminate\Http\Request;

class VehicleOwnerDTO extends AbstractDTO
{
    public $id;
    public $id_customer;
    public $id_vehicle;
    public $start_date;
    public $end_date;
    public $notes;

    /**
     * CustomerDTO constructor.
     * @param ?int $id
     * @param int $id_customer
     * @param int $id_vehicle
     * @param string $start_date
     * @param string $end_date
     * @param string $notes
     */
    public function __construct($id, $id_customer, $id_vehicle, $start_date, $end_date, $notes)
    {
        $this->id = $id;
        $this->id_customer = $id_customer;
        $this->id_vehicle = $id_vehicle;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->notes = $notes;
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
            $request->input('idVehicle') ?? null,
            null,
            null,
            $request->input('notes') ?? null
        );
    }
}
