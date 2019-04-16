<?php

namespace Paloma\Shop\Error;

use Exception;
use GuzzleHttp\Exception\BadResponseException;

class InvalidCouponCode extends Exception
{
    public function __construct(BadResponseException $bae)
    {
        // TODO implement
    }

    public function getErrors()
    {
        return []; // TODO
    }
}