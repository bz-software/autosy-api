<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class CustomerDTO extends AbstractDTO
{
    public $name;
    public $phone_number;
    public $id_workshop;

    /**
     * CustomerDTO constructor.
     * 
     * @param string $name
     * @param string $phoneNumber
     * @param int $idWorkshop
     */
    public function __construct($name, $phoneNumber, $idWorkshop)
    {
        $this->name = $name;
        $this->phone_number = $phoneNumber;
        $this->id_workshop = $idWorkshop;
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
            $request->input('name') ?? null,
            $request->input('phoneNumber') ?? null,
            $request->input('idWorkshop') ?? null
        );
    }
}
