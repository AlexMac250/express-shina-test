<?php

namespace App\DTO;

use App\ParamConverter\JsonInputDto;
use App\Validation\ProductTypeIdConstraint;

#[JsonInputDto]
class ProductByProductTypeDTO
{
    #[ProductTypeIdConstraint]
    public int $productTypeId;
}
