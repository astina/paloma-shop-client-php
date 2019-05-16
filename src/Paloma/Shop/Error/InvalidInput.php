<?php

namespace Paloma\Shop\Error;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class InvalidInput extends AbstractPalomaException
{
    /**
     * @var ValidationError[]
     */
    private $errors;

    public static function ofValidation(ConstraintViolationListInterface $validation)
    {
        $errors = [];

        /** @var ConstraintViolationInterface $violation */
        foreach ($validation as $violation) {
            $errors[] = new ValidationError($violation->getPropertyPath(), $violation->getMessage());
        }

        return new InvalidInput($errors);
    }

    public static function ofHttpResponse(ResponseInterface $response)
    {
        $errors = [];

        try {
            $data = json_decode($response->getBody(), true);

            if (isset($data['errors'])) {
                $errors = $data['errors'];
            }

        } catch (Exception $ignore) {}

        return new InvalidInput($errors);
    }

    public function __construct(array $errors = [])
    {
        parent::__construct('Invalid input', null, null);
        $this->errors = $errors;
    }

    /**
     * @return array]
     */
    public function getValidation(): array
    {
        return [
            'errors' => $this->errors,
        ];
    }

    function getHttpStatus(): int
    {
        return 400;
    }
}