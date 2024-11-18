<?php

namespace App\Enum;

enum ProductTypeEnum: string
{
    case FRUIT = 'fruit';
    case VEGETABLE = 'vegetable';

    public static function values(): array
    {
        return array_map(fn ($value) => $value->value, self::cases());
    }
}
