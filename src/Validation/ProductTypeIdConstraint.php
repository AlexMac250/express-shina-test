<?php

namespace App\Validation;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ProductTypeIdConstraint extends Constraint
{
    public string $message = 'Product type with id {{ value }} does not exists';
    // If the constraint has configuration options, define them as public properties
    public string $mode = 'strict';

    public function validatedBy(): string
    {
        return ProductTypeIdValidator::class;
    }
}
