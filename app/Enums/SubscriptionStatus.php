<?php

namespace App\Enums;

use App\Enums\Traits\HasArrayRepresentation;

enum SubscriptionStatus: int
{
    use HasArrayRepresentation;

    case PENDING = 1;
    case AUTHORIZED = 2;
    case PAYMENT_FAILED = 3;
    case PAUSED = 4;
    case CANCELLED = 5;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::AUTHORIZED => 'Ativa',
            self::PAYMENT_FAILED => 'Pagamento falhou',
            self::PAUSED => 'Pausada',
            self::CANCELLED => 'Cancelada',
        };
    }

    public function isActive(): bool
    {
        return $this === self::AUTHORIZED;
    }

    public function blocksAccess(): bool
    {
        return match ($this) {
            self::AUTHORIZED => false,
            default => true,
        };
    }

    public function canBeReactivated(): bool
    {
        return match ($this) {
            self::CANCELLED,
            self::PAUSED,
            self::PAYMENT_FAILED => true,
            default => false,
        };
    }
}