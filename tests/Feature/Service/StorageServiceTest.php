<?php

use App\Enum\ProductTypeEnum;
use App\Enum\WeightUnitEnum;
use App\Repository\ProductRepository;
use App\Service\StorageService;

beforeEach(function () {
    $kernel = self::bootKernel();

    $application = new Symfony\Bundle\FrameworkBundle\Console\Application($kernel);

    $application->setAutoExit(false);

    $input = new Symfony\Component\Console\Input\ArrayInput(['command' => 'doctrine:migrations:migrate', '--no-interaction' => true]);

    $output = new Symfony\Component\Console\Output\NullOutput();
    $application->run($input, $output);

    $this->container = static::getContainer();

    /* @var StorageService $service */
    $this->storageService = $this->container->get(StorageService::class);
});

it('processes the input json and stores the values in the database', function () {
    $request = file_get_contents('request.json');
    expect($this->storageService->process($request))->toHaveCount(count(ProductTypeEnum::cases()));
    expect($this->container->get(ProductRepository::class)->all())->toHaveCount(count(json_decode($request)));
});

it('converts kilos to grams', function () {
    $input = json_encode([
        ['id' => 1, 'name' => 'Banana', 'type' => 'fruit', 'quantity' => 11, 'unit' => 'kg'],
    ]);

    $this->storageService->process($input);

    $expectedItem = $this->storageService->fruits()[0];

    expect($expectedItem->unit)->toEqual(WeightUnitEnum::GRAM)->and($expectedItem->quantity)->toEqual(11000);
});

it('does not convert gram values', function () {
    $input = json_encode([
        ['id' => 1, 'name' => 'Banana', 'type' => 'fruit', 'quantity' => 11, 'unit' => 'g'],
    ]);

    $this->storageService->process($input);

    $expectedItem = $this->storageService->fruits()[0];

    expect($expectedItem->unit)->toEqual(WeightUnitEnum::GRAM)->and($expectedItem->quantity)->toEqual(11);
});

it('updates a product if it already exists', function () {
    $input = json_encode([
        ['id' => 1, 'name' => 'Banana', 'type' => 'fruit', 'quantity' => 11, 'unit' => 'g'],
    ]);

    $this->storageService->process($input);

    $input = json_encode([
        ['id' => 1, 'name' => 'Yellow Banana', 'type' => 'fruit', 'quantity' => 22, 'unit' => 'g'],
    ]);

    $this->storageService->process($input);

    $expectedItem = $this->container->get(ProductRepository::class)->findBy(['productId' => 1])[0];

    expect($expectedItem->getName())->toEqual('Yellow Banana')->and($expectedItem->getQuantity())->toEqual(22);
});
