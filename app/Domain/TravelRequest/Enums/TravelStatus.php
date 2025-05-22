<?php

namespace App\Domain\TravelRequest\Enums;

/**
 * Primeira vez que utilizo Enums para validar os possíveis status
 */
enum TravelStatus : string
{
    case SOLICITADO = 'solicitado';
    case APROVADO = 'aprovado';
    case CANCELADO = 'cancelado';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
