<?php

namespace App\Entity;

use App\Entity\Interface\ResourcesInterface;
use App\Entity\Trait\ResourcesTrait;
use App\Repository\ProductModelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'product_model')]
#[ORM\UniqueConstraint(name: 'product_model_unique_idx', columns: ['name', 'manufacturer_id', 'product_type_id'])]
#[ORM\Entity(repositoryClass: ProductModelRepository::class)]
class ProductModel implements ResourcesInterface
{
    use ResourcesTrait;

    #[ORM\OneToMany(mappedBy: 'productModel', targetEntity: Product::class, cascade: ['all'], orphanRemoval: true)]
    private Collection $products;

    #[ORM\ManyToOne(targetEntity: ProductType::class, inversedBy: 'productModels')]
    #[ORM\JoinColumn(name: 'product_type_id', referencedColumnName: 'id')]
    private ProductType $productType;

    #[ORM\ManyToOne(targetEntity: Manufacturer::class, inversedBy: 'productModels')]
    #[ORM\JoinColumn(name: 'manufacturer_id', referencedColumnName: 'id')]
    private Manufacturer $manufacturer;

    #[ORM\Column(name: 'name', type: 'string', nullable: false)]
    private string $name;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function setProducts(Collection $products): self
    {
        $this->products = $products;

        return $this;
    }

    public function getProducts(): array
    {
        return $this->products->toArray();
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);

            $product->setProductModel($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
        }

        return $this;
    }

    public function setProductType(ProductType $productType): self
    {
        $this->productType = $productType;

        return $this;
    }

    public function getProductType(): ProductType
    {
        return $this->productType;
    }

    public function setManufacturer(Manufacturer $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getManufacturer(): Manufacturer
    {
        return $this->manufacturer;
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
}
