<?php

namespace App\DTO;

use App\ParamConverter\JsonInputDto;
use App\Validation\ProductIdConstraint;

#[JsonInputDto]
class ProductIdDTO
{
    #[ProductIdConstraint]
    public int $productId;
}
