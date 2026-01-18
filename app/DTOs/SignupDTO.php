<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class SignupDTO
{
    public function __construct(
        public string $name,
        public string $phone_number,
        public string $password,
        public int $workshop_type,
        public string $workshop_name
    ) {}

    /**
     * Cria o DTO a partir do Request validado
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('name') ?? null,
            $request->input('phoneNumber') ?? null,
            $request->input('password') ?? null,
            $request->input('workshop.type') ?? null,
            $request->input('workshop.name') ?? null
        );
    }
}
