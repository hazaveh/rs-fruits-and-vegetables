<?php

namespace App\DTO\Request;

use App\Enum\ProductTypeEnum;
use App\Enum\WeightUnitEnum;
use Symfony\Component\Validator\Constraints as Assert;

class Product implements \JsonSerializable
{
    public function __construct(
        #[Assert\NotBlank]
        public int|string $id,
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $name,
        #[Assert\NotBlank]
        #[Assert\Choice([ProductTypeEnum::VEGETABLE->value, ProductTypeEnum::FRUIT->value])]
        public string $type,
        #[Assert\NotBlank]
        #[Assert\Type('int')]
        public int $quantity,
        #[Assert\NotBlank]
        #[Assert\Choice([WeightUnitEnum::KILO_GRAM->value, WeightUnitEnum::GRAM->value])]
        public string $unit)
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
        ];
    }
}
