<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class ServiceDTO extends AbstractDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public int $duration,
        public int $id_workshop
    ) {}

    /**
     * Cria o DTO a partir do Request validado
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('id') ?? 0,
            $request->input('name') ?? null,
            $request->input('duration') ?? 0,
            0
        );
    }
}
