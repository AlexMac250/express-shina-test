<?php

namespace App\Provider;

use App\Entity\Cart;
use App\Repository\CartRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CurrentCartProvider
{
    /**
     * @var string
     */
    public const CART_KEY_NAME = 'cart_id';

    public function __construct(
        private RequestStack $requestStack,
        private CartRepository $cartRepository
    ) {
    }

    public function getCurrentCart(): Cart
    {
        /** @var Cart $cart */
        $cart = null;

        $cartId = $this->getCartIdFromSession();

        if (null !== $cartId) {
            $cart = $this->cartRepository->findOneBy([
                'id' => $cartId,
            ]);
        }

        if (null === $cart) {
            $cart = new Cart();

            $this->saveCart($cart);
        }

        return $cart;
    }

    public function saveCart(Cart $cart): void
    {
        $this->cartRepository->save($cart, true);

        $session = $this->requestStack->getSession();

        $session->set(self::CART_KEY_NAME, $cart->getId());
        $session->save();
    }

    private function getCartIdFromSession(): ?int
    {
        return (int) $this->requestStack->getSession()->get(self::CART_KEY_NAME);
    }
}
