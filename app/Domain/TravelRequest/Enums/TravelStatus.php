<?php

namespace App\Domain\TravelRequest\Enums;

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
