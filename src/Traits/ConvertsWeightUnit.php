<?php

namespace App\Traits;

use App\Enum\WeightUnitEnum;
use App\Model\Product;

trait ConvertsWeightUnit
{
    /**
     * @param array<int, Product> $items
     *
     * @return array<int, Product>
     */
    public static function convertToKG(array $items): array
    {
        return array_map(function ($item) {
            /* @var $item Product */
            return new Product($item->id, $item->name, $item->type, $item->quantity / WeightUnitEnum::G_KG_MULTIPLIER, WeightUnitEnum::KILO_GRAM);
        }, $items);
    }
}
