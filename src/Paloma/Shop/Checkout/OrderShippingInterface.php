<?php

namespace Paloma\Shop\Checkout;

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