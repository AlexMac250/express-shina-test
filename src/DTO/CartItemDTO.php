<?php

namespace App\DTO;

class CartItemDTO
{
    public function __construct(
        public string $name,
        public float $price,
        public float $totalPrice,
        public int $quantity
    ) {
    }
}
