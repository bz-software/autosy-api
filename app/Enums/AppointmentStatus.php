<?php

namespace App\Enums;

use App\Enums\Traits\HasArrayRepresentation;

enum AppointmentStatus: int
{
    use HasArrayRepresentation;

    case AGENDADO = 1;
    case DIAGNOSTICO = 2;
    case AGUARDANDO_APROVACAO = 3;
    case ANDAMENTO = 4;
    case FINALIZADO = 5;
    case AGUARDANDO_PAGAMENTO = 6;

    public function label(): string
    {
        return match ($this) {
            self::AGENDADO => 'Agendado',
            self::DIAGNOSTICO => 'Em diagnóstico',
            self::AGUARDANDO_APROVACAO => 'Aguardando aprovação',
            self::ANDAMENTO => 'Em andamento',
            self::FINALIZADO => 'Finalizado',
            self::AGUARDANDO_PAGAMENTO => 'Aguardando pagamento'
        };
    }
}
