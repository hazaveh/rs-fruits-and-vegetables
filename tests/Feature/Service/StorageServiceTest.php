<?php

use App\Enum\ProductTypeEnum;
use App\Enum\WeightUnitEnum;
use App\Service\StorageService;

beforeEach(function () {
    self::bootKernel();

    $container = static::getContainer();

    /* @var StorageService $service */
    $this->storageService = $container->get(StorageService::class);
});

it('processes the input json', function () {
    $request = file_get_contents('request.json');
    expect($this->storageService->process($request))->toHaveCount(count(ProductTypeEnum::cases()));
});

it('converts kilos to grams', function () {
    $input = json_encode([
        ['id' => 1, 'name' => 'Banana', 'type' => 'fruit', 'quantity' => 11, 'unit' => 'kg'],
    ]);

    $this->storageService->process($input);

    $expectedItem = $this->storageService->fruits()[0];

    expect($expectedItem->unit)->toEqual(WeightUnitEnum::GRAM)->and($expectedItem->quantity)->toEqual(11000);
});

it('does not convert gram values', function() {
    $input = json_encode([
        ['id' => 1, 'name' => 'Banana', 'type' => 'fruit', 'quantity' => 11, 'unit' => 'g'],
    ]);

    $this->storageService->process($input);

    $expectedItem = $this->storageService->fruits()[0];

    expect($expectedItem->unit)->toEqual(WeightUnitEnum::GRAM)->and($expectedItem->quantity)->toEqual(11);
});


