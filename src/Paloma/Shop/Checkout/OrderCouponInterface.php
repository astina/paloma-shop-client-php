<?php

namespace Paloma\Shop\Checkout;

interface OrderCouponInterface
{
    /**
     * @return string The coupon code
     */
    function getCode(): string;
}