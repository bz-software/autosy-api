<?php

namespace App\Http\Resources\Abstracts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NameValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->name ?? null,
            "value" => $this->value ?? null
        ];
    }
}
