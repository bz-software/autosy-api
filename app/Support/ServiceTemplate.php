<?php

namespace App\Support;

use App\Enums\WorkshopType;

class ServiceTemplate
{
    public static function byType(int $type): array
    {
        return match ($type) {
            WorkshopType::CAR_WASH->value => self::lavaJato(),
            WorkshopType::DETAILING->value => self::esteticaAutomotiva(),
            default => [],
        };
    }

    private static function lavaJato(): array
    {
        return [
            [ 'name' => 'Lavagem Simples', 'duration' => 30 ],
            [ 'name' => 'Lavagem Completa', 'duration' => 60 ],
            [ 'name' => 'Lavagem Premium', 'duration' => 90 ],
            [ 'name' => 'Lavagem de Motor', 'duration' => 45 ],
            [ 'name' => 'Higienização Interna', 'duration' => 60 ],
            [ 'name' => 'Cera Líquida', 'duration' => 30 ],
            [ 'name' => 'Aspiração Completa', 'duration' => 30 ],
            [ 'name' => 'Lavagem + Cera + Aspiração', 'duration' => 90 ],
        ];
    }

    private static function esteticaAutomotiva(): array
    {
        return [
            [ 'name' => 'Polimento Técnico', 'duration' => 180 ],
            [ 'name' => 'Cristalização de Pintura', 'duration' => 240 ],
            [ 'name' => 'Vitrificação', 'duration' => 300 ],
            [ 'name' => 'Higienização Interna Completa', 'duration' => 120 ],
            [ 'name' => 'Hidratação de Couro', 'duration' => 90 ],
            [ 'name' => 'Cristalização de Vidros', 'duration' => 60 ],
            [ 'name' => 'Revitalização de Plásticos', 'duration' => 60 ],
            [ 'name' => 'Proteção Cerâmica', 'duration' => 360 ],
        ];
    }
}
