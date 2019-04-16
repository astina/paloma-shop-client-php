<?php

namespace Paloma\Shop\Error;

class ValidationError
{
    private $property;

    private $message;

    public function __construct(string $property, string $message)
    {
        $this->property = $property;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

}