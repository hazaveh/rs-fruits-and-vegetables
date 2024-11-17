<?php

namespace App\Model;

use App\Enum\ProductTypeEnum;
use App\Enum\WeightUnitEnum;

class Product
{
    public function __construct(public readonly int $id, public readonly string $name, public readonly ProductTypeEnum $type, public readonly int $quantity, public readonly WeightUnitEnum $unit)
    {
    }

    /**
     * @param string[] $item
     */
    public static function createFromArray(array $item): Product
    {
        $item['type'] = ProductTypeEnum::from($item['type']);
        if (WeightUnitEnum::KILO_GRAM === WeightUnitEnum::from($item['unit'])) {
            $item['quantity'] = intval($item['quantity']) * WeightUnitEnum::G_KG_MULTIPLIER;
        }

        return new self($item['id'], $item['name'], $item['type'], $item['quantity'], WeightUnitEnum::GRAM);
    }
}
