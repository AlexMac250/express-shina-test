<?php

namespace App\Controller\API\v1;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Service\Attribute\Required;

class AbstractController extends AbstractFOSRestController
{
    #[Required]
    public ValidatorInterface $validator;

    #[Required]
    public SerializerInterface $serializer;

    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }

    protected function createValidatorErrorResponse(ConstraintViolationListInterface $errors): JsonResponse
    {
        $response = [];

        foreach ($errors as $error) {
            $response[] = [
                'property' => $error->getPropertyPath(),
                'message' => $error->getMessage(),
            ];
        }

        return $this->json(['validator' => ['errors' => $response]]);
    }

    protected function response(mixed $data): JsonResponse
    {
        $responseData = $this->serializer->serialize($data, 'json');

        return $this->json($responseData);
    }
}
