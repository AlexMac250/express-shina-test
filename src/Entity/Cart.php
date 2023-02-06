<?php

namespace App\Entity;

use App\Entity\Interface\ResourcesInterface;
use App\Entity\Trait\ResourcesTrait;
use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'carts')]
#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart implements ResourcesInterface
{
    use ResourcesTrait;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartItem::class, cascade: ['all'], orphanRemoval: true)]
    private Collection $cartItems;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
    }

    public function addProduct(Product $product): CartItem
    {
        $cartItem = $this->getCartItemByProduct($product);

        if (null !== $cartItem) {
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
        } else {
            $cartItem = new CartItem();
            $cartItem->setProduct($product);
            $cartItem->setQuantity(1);

            $this->addCartItem($cartItem);
        }

        return $cartItem;
    }

    public function removeProduct(Product $product): self
    {
        $cartItem = $this->getCartItemByProduct($product);

        if (null !== $cartItem) {
            if (1 === $cartItem->getQuantity()) {
                $this->removeCartItem($cartItem);

                return $this;
            }

            $cartItem->setQuantity($cartItem->getQuantity() - 1);
        }

        return $this;
    }

    public function setCartItems(ArrayCollection $cartItems): self
    {
        $this->cartItems = $cartItems;

        return $this;
    }

    /**
     * @return array|CartItem[]
     */
    public function getCartItems(): array
    {
        return $this->cartItems->toArray();
    }

    public function addCartItem(CartItem $cartItem): self
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems->add($cartItem);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): self
    {
        if ($this->cartItems->contains($cartItem)) {
            $this->cartItems->removeElement($cartItem);
        }

        $cartItem->setCart(null);

        return $this;
    }

    public function getTotalPrice(): float
    {
        $totalPrice = 0;

        /** @var CartItem $cartItem */
        foreach ($this->cartItems as $cartItem) {
            $totalPrice += $cartItem->getTotalPrice();
        }

        return $totalPrice;
    }

    public function getQuantity(): int
    {
        return count($this->getCartItems());
    }

    public function getCartItemByProduct(Product $product): ?CartItem
    {
        return $this->cartItems->findFirst(
            function ($key, CartItem $cartItem) use ($product) {
                return $cartItem->getProduct() === $product;
            }
        );
    }
}
