<?php

namespace App\DTO;

use App\Entity\Product;

class ProductDTO
{
    public int $id;
    public int $productTypeId;
    public int $productModelId;
    public string $name;
    public float $price;

    public function __construct(Product $product)
    {
        $productModel = $product->getProductModel();

        $this->id = $product->getId();
        $this->productTypeId = $productModel->getProductType()->getId();
        $this->productModelId = $productModel->getId();
        $this->name = $product->getName();
        $this->price = $product->getPrice();
    }
}
