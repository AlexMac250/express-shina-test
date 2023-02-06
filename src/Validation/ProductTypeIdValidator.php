<?php

namespace App\Validation;

use App\Repository\ProductTypeRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProductTypeIdValidator extends ConstraintValidator
{
    public function __construct(
        private ProductTypeRepository $productTypeRepository
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof ProductTypeIdConstraint) {
            return;
        }

        if (null === $value) {
            $this->context->addViolation(
                'Value can not be null',
            );

            return;
        }

        if (null === $this->productTypeRepository->find($value)) {
            $this->context->addViolation(
                $constraint->message,
                [
                    '{{ value }}' => $value,
                ]
            );
        }
    }
}
