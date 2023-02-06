<?php

namespace App\Controller\API\v1;

use App\DTO\ProductByProductTypeDTO;
use App\DTO\ProductDTO;
use App\Entity\Product;
use App\Entity\ProductModel;
use App\Repository\ProductTypeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(
        private ProductTypeRepository $productTypeRepository
    ) {
    }

    #[Route('/products', methods: ['POST'])]
    #[ParamConverter('productTypeId', class: ProductByProductTypeDTO::class)]
    public function getListAction(ProductByProductTypeDTO $productByProductTypeDTO): JsonResponse
    {
        $errors = $this->validator->validate($productByProductTypeDTO);

        if (count($errors) > 0) {
            return $this->createValidatorErrorResponse($errors);
        }

        $productTypeId = $productByProductTypeDTO->productTypeId;

        $productType = $this->productTypeRepository->find($productTypeId);

        $productModels = $productType?->getProductModels();
        $products = [];

        /** @var ProductModel $productModel */
        foreach ($productModels as $productModel) {
            $productDTOs = array_map(
                static function (Product $product) {
                    return new ProductDTO($product);
                },
                $productModel->getProducts()
            );

            $products = [...$products, ...$productDTOs];
        }

        return $this->json($products);
    }
}
