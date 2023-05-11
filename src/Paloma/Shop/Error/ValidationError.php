<?php

namespace Paloma\Shop\Error;

class ValidationError
{
    private string $property;

    private string $message;

    public function __construct(string $property, string $message)
    {
        $this->property = $property;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

}