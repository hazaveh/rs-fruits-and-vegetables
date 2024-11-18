<?php

namespace App\Service;

use App\Collection\ProductCollection;
use App\Contract\ParserInterface;
use App\Enum\ProductTypeEnum;
use App\Model\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class StorageService
{
    /** @var array<string, ProductCollection> */
    private array $categories;

    public function __construct(private ParserInterface $parser, private EntityManagerInterface $entityManager, private ProductRepository $productRepository)
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
            $this->categories[$item['type']]->add($product = Product::createFromArray($item));
            $this->save($product);
        }

        $this->entityManager->flush();

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

    private function save(Product $item)
    {
        $product = $this->productRepository->firstOrCreate($item->id);
        $product->setName($item->name);
        $product->setType($item->type->value);
        $product->setQuantity($item->quantity);

        if (!$product->getId()) {
            $product->setProductId($item->id);
        }

        $this->entityManager->persist($product);
    }
}
