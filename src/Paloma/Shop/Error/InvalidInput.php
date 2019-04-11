<?php

namespace Paloma\Shop\Error;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class InvalidInput extends Exception
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $validation;

    public function __construct(ConstraintViolationListInterface $validation = null)
    {
        parent::__construct('Invalid input', null, null);
        $this->validation = $validation;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getValidation(): ConstraintViolationListInterface
    {
        return $this->validation;
    }
}