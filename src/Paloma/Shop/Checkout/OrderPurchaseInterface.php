<?php

namespace Paloma\Shop\Checkout;

interface OrderPurchaseInterface
{
    /**
     * @return string Order number
     */
    function getOrderNumber(): string;
}