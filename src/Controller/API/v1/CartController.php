<?php

namespace App\Controller\API\v1;

use App\DTO\CartDTO;
use App\DTO\CartItemDTO;
use App\DTO\ProductIdDTO;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Provider\CurrentCartProvider;
use App\Repository\CartItemRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public function __construct(
        private CurrentCartProvider $currentCartProvider,
        private EntityManagerInterface $entityManager,
        private CartItemRepository $cartItemRepository,
        private ProductRepository $productRepository,
    ) {
    }

    #[Route('/cart', methods: ['GET'])]
    public function getCartAction(): JsonResponse
    {
        $cart = $this->currentCartProvider->getCurrentCart();

        $cartItemDTOs = array_map(static function (CartItem $cartItem): CartItemDTO {
            return new CartItemDTO(
                $cartItem->getProduct()->getName(),
                $cartItem->getProduct()->getPrice(),
                $cartItem->getTotalPrice(),
                $cartItem->getQuantity(),
            );
        }, $cart->getCartItems());

        $cartDTO = new CartDTO($cart->getQuantity(), $cartItemDTOs, $cart->getTotalPrice());

        return $this->json($cartDTO);
    }

    #[Route('/cart/add', methods: ['POST'])]
    #[ParamConverter('productId', ProductIdDTO::class)]
    public function addAction(ProductIdDTO $productIdDTO): JsonResponse
    {
        $errors = $this->validator->validate($productIdDTO);

        if (count($errors) > 0) {
            return $this->createValidatorErrorResponse($errors);
        }

        $cart = $this->currentCartProvider->getCurrentCart();

        /** @var Product $product */
        $product = $this->productRepository->find($productIdDTO->productId);

        $cartItem = $cart->getCartItemByProduct($product);

        if (null !== $cartItem) {
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
        } else {
            $cartItem = new CartItem();
            $cartItem->setProduct($product);
            $cartItem->setQuantity(1);
            $cartItem->setCart($cart);

            $this->cartItemRepository->save($cartItem);
        }

        $this->entityManager->flush();

        return $this->json(['success']);
    }

    #[Route('/cart/remove', methods: ['POST'])]
    #[ParamConverter('productId', ProductIdDTO::class)]
    public function removeAction(ProductIdDTO $productIdDTO): JsonResponse
    {
        $errors = $this->validator->validate($productIdDTO);

        if (count($errors) > 0) {
            return $this->createValidatorErrorResponse($errors);
        }

        $cart = $this->currentCartProvider->getCurrentCart();

        /** @var Product $product */
        $product = $this->productRepository->find($productIdDTO->productId);

        $cartItem = $cart->getCartItemByProduct($product);

        if (null !== $cartItem) {
            if (1 !== $cartItem->getQuantity()) {
                $cartItem->setQuantity($cartItem->getQuantity() - 1);
            } else {
                $cart->removeCartItem($cartItem);
                $this->cartItemRepository->remove($cartItem);
            }
        }

        $this->entityManager->flush();

        return $this->json(['success']);
    }
}
