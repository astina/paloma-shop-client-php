<?php

namespace Paloma\Shop\Error;

use GuzzleHttp\Exception\BadResponseException;

class InvalidCouponCode extends AbstractPalomaException
{
    public function __construct(BadResponseException $bae)
    {
        // TODO implement
    }

    public function getErrors()
    {
        return []; // TODO
    }

    function getHttpStatus(): int
    {
        return 400;
    }
}