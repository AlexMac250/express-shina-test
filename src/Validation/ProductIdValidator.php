<?php

namespace App\Validation;

use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintValidator;

class ProductIdValidator extends ConstraintValidator
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductIdConstraint) {
            return;
        }

        if (false === $value || (empty($value) && '0' != $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(NotBlank::IS_BLANK_ERROR)
                ->addViolation()
            ;
        }

        if (null === $value) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(NotNull::IS_NULL_ERROR)
                ->addViolation()
            ;
        }

        if (null !== $value && null === $this->productRepository->find($value)) {
            $this->context->addViolation(
                $constraint->message,
                [
                    '{{ value }}' => $value,
                ]
            );
        }
    }
}
