<?php

namespace App\Validation;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ProductIdConstraint extends Constraint
{
    public string $message = 'Product with id {{ value }} does not exists';
    // If the constraint has configuration options, define them as public properties
    public string $mode = 'strict';

    public function validatedBy(): string
    {
        return ProductIdValidator::class;
    }
}
