<?php

namespace App\Entity;

use App\Entity\Interface\ResourcesInterface;
use App\Entity\Trait\ResourcesTrait;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'products')]
#[ORM\UniqueConstraint(name: 'product_name_product_model_unique_idx', columns: ['name', 'product_model_id'])]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product implements ResourcesInterface
{
    use ResourcesTrait;

    #[ORM\ManyToOne(targetEntity: ProductModel::class, inversedBy: 'products')]
    #[ORM\JoinColumn(name: 'product_model_id', referencedColumnName: 'id')]
    private ProductModel $productModel;

    #[ORM\Column(name: 'name', type: 'string', nullable: false)]
    private string $name;

    #[ORM\Column(name: 'price', type: 'float', nullable: false)]
    private float $price;

    public function setProductModel(ProductModel $productModel): self
    {
        $this->productModel = $productModel;

        return $this;
    }

    public function getProductModel(): ProductModel
    {
        return $this->productModel;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
