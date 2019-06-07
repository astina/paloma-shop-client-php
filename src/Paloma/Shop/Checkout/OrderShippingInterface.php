<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\Common\AddressInterface;

interface OrderShippingInterface
{
    /**
     * @return OrderShippingMethodInterface Shipping method
     */
    function getShippingMethod(): OrderShippingMethodInterface;

    /**
     * @return AddressInterface|null Shipping address
     */
    function getAddress(): ?AddressInterface;
}