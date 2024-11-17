<?php

use App\Enum\ProductAttributesEnum;
use App\Parsers\JsonParser;

beforeEach(function () {
    $this->parser = new JsonParser();
});

it('validates input json', function () {
    $this->parser->parse('invalid json input');
})->throws(InvalidArgumentException::class);

it('validates mandatory attributes', function () {
    $this->parser->parse(json_encode([['id' => 1, 'name' => 'x']]));
})->throws(InvalidArgumentException::class);

it('parses data', function () {
    $parser = new JsonParser();
    $input = file_get_contents('request.json');
    $output = $parser->parse($input);

    expect($output)->toBeArray();

    foreach (ProductAttributesEnum::cases() as $attribute) {
        expect($output[0])->toHaveKey($attribute->value);
    }
});
