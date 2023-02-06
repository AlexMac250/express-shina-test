<?php

namespace App\Entity;

use App\Entity\Interface\ResourcesInterface;
use App\Entity\Trait\ResourcesTrait;
use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'cart_items')]
#[ORM\Entity(repositoryClass: CartItemRepository::class)]
class CartItem implements ResourcesInterface
{
    use ResourcesTrait;

    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'cartItems')]
    #[ORM\JoinColumn(name: 'cart_id', referencedColumnName: 'id')]
    private ?Cart $cart;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    private Product $product;

    #[ORM\Column(name: 'quantity', type: 'integer')]
    private int $quantity;

    public function setCart(?Cart $cart = null): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return $this
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTotalPrice(): float
    {
        return $this->product->getPrice() * $this->quantity;
    }
}
