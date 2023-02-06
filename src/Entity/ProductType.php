<?php

namespace App\Entity;

use App\Entity\Interface\ResourcesInterface;
use App\Entity\Trait\ResourcesTrait;
use App\Repository\ProductTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'product_types')]
#[ORM\Entity(repositoryClass: ProductTypeRepository::class)]
class ProductType implements ResourcesInterface
{
    use ResourcesTrait;

    #[ORM\OneToMany(mappedBy: 'productType', targetEntity: ProductModel::class, orphanRemoval: true)]
    private Collection $productModels;

    #[ORM\Column(name: 'name', type: 'string', unique: true, nullable: false)]
    private string $name;

    public function __construct()
    {
        $this->productModels = new ArrayCollection();
    }

    public function setProductModels(Collection $productModels): self
    {
        $this->productModels = $productModels;

        return $this;
    }

    public function getProductModels(): Collection
    {
        return $this->productModels;
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

    public function addProductModel(ProductModel $productModel): self
    {
        if (!$this->productModels->contains($productModel)) {
            $this->productModels->add($productModel);

            $productModel->setProductType($this);
        }

        return $this;
    }

    public function removeProductModel(ProductModel $productModel): self
    {
        if ($this->productModels->contains($productModel)) {
            $this->productModels->removeElement($productModel);
        }

        return $this;
    }
}
