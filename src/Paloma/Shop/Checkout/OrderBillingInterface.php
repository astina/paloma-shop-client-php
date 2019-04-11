<?php

namespace Paloma\Shop\Checkout;

interface OrderBillingInterface
{
    /**
     * @return string Payment method name
     */
    function getPaymentMethod(): string;

    /**
     * @return AddressInterface|null Billing address
     */
    function getAddress(): ?AddressInterface;
}