<?php

namespace Paloma\Shop\Error;

use Psr\Http\Message\ResponseInterface;

class InvalidCouponCode extends InvalidInput
{
    public static function ofHttpResponse(ResponseInterface $response)
    {
        $errors = self::collectErrors($response);

        return new InvalidCouponCode($errors);
    }

    function getHttpStatus(): int
    {
        return 400;
    }
}