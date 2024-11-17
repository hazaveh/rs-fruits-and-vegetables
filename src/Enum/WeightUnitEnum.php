<?php

namespace App\Enum;

enum WeightUnitEnum: string
{
    case GRAM = 'g';
    case KILO_GRAM = 'kg';

    public const G_KG_MULTIPLIER = 1000;
}
