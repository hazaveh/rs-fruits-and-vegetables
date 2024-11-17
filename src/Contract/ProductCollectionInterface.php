<?php

namespace App\Contract;

use App\Enum\WeightUnitEnum;
use App\Model\Product;

interface ProductCollectionInterface
{
    public function add(Product $product): self;

    public function remove(Product $product): self;

    /**
     * @return array<int, Product>
     */
    public function list(WeightUnitEnum $weightUnitEnum): array;

    /**
     * @return array<int, Product>
     */
    public function search(string $search): array;
}
