<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\Common\AddressInterface;

interface OrderShippingInterface
{
    /**
     * @return string Shipping method name
     */
    function getShippingMethod(): string;

    /**
     * @return AddressInterface|null Shipping address
     */
    function getAddress(): ?AddressInterface;
}