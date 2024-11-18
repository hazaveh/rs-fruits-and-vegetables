<?php

namespace App\Controller;

use App\Collection\ProductCollection;
use App\DTO\Request\Product;
use App\Entity\Product as EntityProduct;
use App\Model\Product as ModelProduct;
use App\Repository\ProductRepository;
use App\Service\StorageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository, private StorageService $storageService)
    {
    }

    #[Route('/products')]
    public function index(): JsonResponse
    {
        $productCollection = new ProductCollection(array_map(fn (EntityProduct $entity) => ModelProduct::createFromDatabaseEntity($entity), $this->productRepository->findAll()));

        return $this->json($productCollection);
    }

    #[Route('/products/add', methods: ['POST'])]
    public function store(
        #[MapRequestPayload] Product $product,
    ) {
        /* I'm putting a single Product into an array to reuse the StorageService functionality as it works for CLI */
        $this->storageService->process(json_encode([$product]));

        return $this->json($product, Response::HTTP_CREATED);
    }
}
