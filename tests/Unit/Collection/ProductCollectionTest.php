<?php

use App\Collection\ProductCollection;
use App\Enum\WeightUnitEnum;
use App\Model\Product;

it('can contain products', function () {
    $items = [
        mock(Product::class),
        mock(Product::class),
        mock(Product::class),
    ];
    expect(new ProductCollection($items))->toHaveCount(3);
});

it('can add products', function () {
    $collection = new ProductCollection();
    $collection->add(mock(Product::class));
    expect($collection->count())->toBe(1);
});

it('can remove products', function () {
    $collection = new ProductCollection([mock(Product::class)]);
    $product = Product::createFromArray(['id' => 1, 'name' => 'Banana', 'type' => 'fruit', 'quantity' => 11, 'unit' => 'g']);
    $collection->add($product);
    expect($collection)->toHaveCount(2);
    $collection->remove($product);
    expect($collection)->toHaveCount(1);
});

it('can list products', function () {
    $items = [
        Product::createFromArray(['id' => 1, 'name' => 'Banana', 'type' => 'fruit', 'quantity' => 11, 'unit' => 'g']),
        Product::createFromArray(['id' => 1, 'name' => 'Apple', 'type' => 'fruit', 'quantity' => 20, 'unit' => 'g']),
    ];
    $collection = new ProductCollection($items);
    expect($collection->list())->toBeArray()->toHaveCount(2);
});

it('can search products', function () {
    $items = [
        Product::createFromArray(['id' => 1, 'name' => 'Banana', 'type' => 'fruit', 'quantity' => 1100, 'unit' => 'g']),
        Product::createFromArray(['id' => 1, 'name' => 'Apple', 'type' => 'fruit', 'quantity' => 20000, 'unit' => 'g']),
        Product::createFromArray(['id' => 1, 'name' => 'Kiwi', 'type' => 'fruit', 'quantity' => 20000, 'unit' => 'g']),
        Product::createFromArray(['id' => 1, 'name' => 'avacado', 'type' => 'fruit', 'quantity' => 20000, 'unit' => 'g']),
    ];
    $collection = new ProductCollection($items);
    /** @var Product[] $result */
    $result = $collection->search('banana');
    // dd($search);
    expect($result)->toBeArray()->toHaveCount(1)->and(reset($result)->quantity)->toBe(1100);
});

it('can convert list results to kilos', function () {
    $items = [
        Product::createFromArray(['id' => 1, 'name' => 'Banana', 'type' => 'fruit', 'quantity' => 2000, 'unit' => 'g']),
    ];
    $collection = new ProductCollection($items);
    expect($collection->list(WeightUnitEnum::KILO_GRAM)[0]->quantity)->toBe(2);
});

it('can convert search results to kilos', function () {
    $items = [
        Product::createFromArray(['id' => 1, 'name' => 'Banana', 'type' => 'fruit', 'quantity' => 1000, 'unit' => 'g']),
        Product::createFromArray(['id' => 1, 'name' => 'Apple', 'type' => 'fruit', 'quantity' => 20000, 'unit' => 'g']),
    ];
    $collection = new ProductCollection($items);
    /** @var Product[] $search */
    $search = $collection->search('banana', WeightUnitEnum::KILO_GRAM);
    expect($search[0]->quantity)->toBe(1);
});
