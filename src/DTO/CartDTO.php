<?php

namespace App\DTO;

class CartDTO
{
    public function __construct(
        public int $quantity,
        public array $cartItems,
        public float $totalPrice
    ) {
    }
}
