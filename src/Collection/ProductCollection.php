<?php

namespace App\Collection;

use App\Contract\ProductCollectionInterface;
use App\Enum\WeightUnitEnum;
use App\Model\Product;
use App\Traits\Arrayable;
use App\Traits\ConvertsWeightUnit;

class ProductCollection implements \Countable, \ArrayAccess, ProductCollectionInterface, \JsonSerializable
{
    use Arrayable;
    use ConvertsWeightUnit;

    /**
     * @param array<int, Product> $items
     */
    public function __construct(protected array $items = [])
    {
    }

    public function add(Product $product): self
    {
        $this->items[] = $product;

        return $this;
    }

    public function remove(Product $product): self
    {
        $this->offsetUnset(array_search($product, $this->items));

        return $this;
    }

    public function search(string $search, WeightUnitEnum $weightUnitEnum = WeightUnitEnum::GRAM): array
    {
        $results = array_filter($this->items, fn ($item) => str_contains(strtolower($item->name), strtolower($search)));

        return WeightUnitEnum::KILO_GRAM === $weightUnitEnum ? self::convertToKG($results) : $results;
    }

    /**
     * @return Product[]
     */
    public function list(WeightUnitEnum $weightUnitEnum = WeightUnitEnum::GRAM): array
    {
        return WeightUnitEnum::KILO_GRAM === $weightUnitEnum ? self::convertToKG($this->items) : $this->items;
    }

    public function jsonSerialize(): array
    {
        return $this->items;
    }
}
