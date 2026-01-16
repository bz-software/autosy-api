<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class AuthDTO extends AbstractDTO
{
    public $phone_number;
    public $password;

    /**
     * AuthDTO constructor.
     * 
     * @param string $phoneNumber
     * @param string $password
     */
    public function __construct($phoneNumber, $password)
    {
        $this->phone_number = $phoneNumber;
        $this->password = $password;
    }

    /**
     * Método para criar o DTO a partir de uma requisição.
     * 
     * @param Request $request
     * @return AuthDTO
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('phoneNumber') ?? null,
            $request->input('password') ?? null
        );
    }
}
