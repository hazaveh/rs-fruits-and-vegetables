<?php

namespace App\Parsers;

use App\Contract\ParserInterface;
use App\Enum\ProductAttributesEnum;

class JsonParser implements ParserInterface
{
    /**
     * @return array<int, array<string, string>>
     */
    public function parse(string $data): array
    {
        return $this->validated($data);
    }

    /**
     * @return array<int, string[]>
     */
    public function validated(string $input): array
    {
        $data = json_decode($input, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException('Invalid JSON structure.');
        }

        foreach ($data as $item) {
            foreach (ProductAttributesEnum::cases() as $attribute) {
                if (!array_key_exists($attribute->value, $item)) {
                    throw new \InvalidArgumentException("Missing Product Attribute `{$attribute->value}` in product id: {$item['id']}");
                }
            }
        }

        return $data;
    }
}
