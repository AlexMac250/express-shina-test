<?php

namespace App\ParamConverter;

use App\Exception\JsonInputDtoValidationException;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JsonInputDtoParamConverter implements \Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface
{
    private static bool $wasExecuted = false;

    private SerializerInterface $serializer;

    private ValidatorInterface $validator;
    private ?bool $throwExceptions;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        bool $handleViolations = true
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->throwExceptions = $handleViolations;
    }

    public static function wasExecuted(): bool
    {
        return self::$wasExecuted;
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        if ($request->getContent() && 'json' === $request->getContentTypeFormat()) {
            $className = $configuration->getClass();
            $object = $this->serializer->deserialize($request->getContent(), $className, 'json');

            $errors = $this->validator->validate($object);

            if ($this->throwExceptions && $errors->count() >= 1) {
                throw new JsonInputDtoValidationException($errors, $object);
            }

            $request->attributes->set(ConstraintViolationListParamConverter::VALIDATION_ERRORS_ARGUMENT, $errors);
            $request->attributes->set($configuration->getName(), $object);

            self::$wasExecuted = true;

            return true;
        }

        return false;
    }

    public function supports(ParamConverter $configuration): bool
    {
        if (!$configuration->getClass()) {
            return false;
        }

        $reflection = new \ReflectionClass($configuration->getClass());
        if (!$reflection->getAttributes(JsonInputDto::class)) {
            return false;
        }

        return true;
    }
}
