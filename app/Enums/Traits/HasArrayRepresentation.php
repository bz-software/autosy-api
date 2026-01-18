<?php

namespace App\Enums\Traits;

trait HasArrayRepresentation
{
    public static function toArray(): array
    {
        return array_map(
            fn (self $case) => [
                'id' => $case->value,
                'label' => method_exists($case, 'label') ? $case->label() : null,
            ],
            self::cases()
        );
    }

    public static function findById(int|string $id): ?self
    {
        return self::tryFrom($id);
    }
}
