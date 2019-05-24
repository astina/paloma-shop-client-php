<?php

namespace Paloma\Shop\Checkout;

interface ShippingMethodInterface
{
    /**
     * @return string Shipping method name
     */
    function getName(): string;

    /**
     * @return string Shipping costs as formatted string including currency symbol (e.g. "CHF 12.80")
     */
    function getPrice(): string;

    /**
     * @return bool True, if this shipping method is set for the current order
     */
    function isSelected(): bool;

    /**
     * @return bool True, if shipping method price is zero
     */
    function isFree(): bool;
}