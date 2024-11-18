<?php

use App\Repository\ProductRepository;

beforeEach(function () {
    $kernel = self::bootKernel();

    $application = new Symfony\Bundle\FrameworkBundle\Console\Application($kernel);

    $application->setAutoExit(false);

    $input = new Symfony\Component\Console\Input\ArrayInput(['command' => 'doctrine:migrations:migrate', '--no-interaction' => true]);

    $output = new Symfony\Component\Console\Output\NullOutput();
    $application->run($input, $output);
    $this->client = createClient($kernel);
    $this->container = static::getContainer();
});

it('returns list of products', function () {
    $response = $this->client->request('GET', '/products');

    expect($this->client->getResponse()->getStatusCode())->toBe(200);
});

it('can add a product', function () {
    $response = $this->client->request('POST', '/products/add', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
        'id' => 123,
        'name' => 'Banana',
        'type' => 'fruit',
        'quantity' => 11,
        'unit' => 'kg',
    ]));
    expect($this->client->getResponse()->getStatusCode())->toBe(201);
    $expectedItem = $this->container->get(ProductRepository::class)->findBy(['productId' => 123])[0];
    expect($expectedItem->getName())->toEqual('Banana')->and($expectedItem->getQuantity())->toEqual(11000);
});
