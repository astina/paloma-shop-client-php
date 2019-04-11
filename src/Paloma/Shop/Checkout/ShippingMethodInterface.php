<?php

namespace Paloma\Shop\Checkout;

interface ShippingMethodInterface
{
    /**
     * @return string Shipping method name
     */
    function getName(): string;

    /**
     * @return bool True, if this shipping method is set for the current order
     */
    function isSelected(): bool;
}