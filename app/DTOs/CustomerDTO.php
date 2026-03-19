<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class CustomerDTO extends AbstractDTO
{
    public $id;
    public $name;
    public $phone_number;

    /**
     * CustomerDTO constructor.
     * 
     * @param int|null $id
     * @param string $name
     * @param string $phoneNumber
     */
    public function __construct($id, $name, $phoneNumber)
    {
        $this->id = $id;
        $this->name = $name;
        $this->phone_number = $phoneNumber;
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
            $request->input('name') ?? null,
            $request->input('phoneNumber') ?? null
        );
    }
}
