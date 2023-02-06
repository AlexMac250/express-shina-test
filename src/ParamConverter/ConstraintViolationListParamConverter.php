<?php

namespace App\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationListParamConverter implements \Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface
{
    public const VALIDATION_ERRORS_ARGUMENT = 'validationErrorList';

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $errors = $request->attributes->get(self::VALIDATION_ERRORS_ARGUMENT);

        if (null !== $errors) {
            $request->attributes->set($configuration->getName(), $errors);

            return true;
        }

        return false;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return ConstraintViolationListInterface::class === $configuration->getClass()
            && JsonInputDtoParamConverter::wasExecuted();
    }
}
