<?php

namespace Paloma\Shop\Checkout;

class OrderCoupon implements OrderCouponInterface
{
    private $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    function getCode(): string
    {
        return $this->code;
    }
}