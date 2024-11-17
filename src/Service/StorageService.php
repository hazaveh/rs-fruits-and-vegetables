<?php

namespace App\Service;

use App\Collection\ProductCollection;
use App\Contract\ParserInterface;
use App\Enum\ProductTypeEnum;
use App\Model\Product;

class StorageService
{
    /** @var array<string, ProductCollection> */
    private array $categories;

    public function __construct(private ParserInterface $parser)
    {
        foreach (ProductTypeEnum::cases() as $type) {
            $this->categories[$type->value] = new ProductCollection();
        }
    }

    /**
     * @return ProductCollection[]
     */
    public function process(string $input): array
    {
        $items = $this->parser->parse($input);

        foreach ($items as $item) {
            $this->categories[$item['type']]->add(Product::createFromArray($item));
        }

        return $this->categories;
    }

    public function fruits(): ProductCollection
    {
        return $this->categories[ProductTypeEnum::FRUIT->value];
    }

    public function vegetables(): ProductCollection
    {
        return $this->categories[ProductTypeEnum::VEGETABLE->value];
    }
}
