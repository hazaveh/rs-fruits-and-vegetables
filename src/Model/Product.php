<?php

namespace App\Model;

use App\Entity\Product as EntityProduct;
use App\Enum\ProductTypeEnum;
use App\Enum\WeightUnitEnum;

class Product implements \JsonSerializable
{
    public function __construct(public readonly string|int $id, public readonly string $name, public readonly ProductTypeEnum $type, public readonly int $quantity, public readonly WeightUnitEnum $unit)
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

    public static function createFromDatabaseEntity(EntityProduct $entity): Product
    {
        return new self($entity->getProductId(), $entity->getName(), ProductTypeEnum::from($entity->getType()), $entity->getQuantity(), WeightUnitEnum::GRAM);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type->value,
            'quantity' => $this->quantity,
            'unit' => $this->unit->value,
        ];
    }
}
