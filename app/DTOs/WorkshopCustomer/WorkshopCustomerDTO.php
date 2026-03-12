<?php

namespace App\DTOs\WorkshopCustomer;

use App\DTOs\AbstractDTO;
use Illuminate\Http\Request;

class WorkshopCustomerDTO extends AbstractDTO
{
    public $id;
    public $id_workshop;
    public $id_customer;

    /**
     * CustomerDTO constructor.
     * 
     * @param ?int $id
     * @param int $phoneNumber
     * @param int $idWorkshop
     */
    public function __construct($id, $id_workshop, $id_customer)
    {
        $this->id = $id;
        $this->id_workshop = $id_workshop;
        $this->id_customer = $id_customer;
    }

    /**
     * Método para criar o DTO a partir de uma requisição.
     * 
     * @param Request $request
     * @return CustomerDTO
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('id') ?? null,
            $request->input('idWorkshop') ?? null,
            $request->input('idCustomer') ?? null,
        );
    }
}
