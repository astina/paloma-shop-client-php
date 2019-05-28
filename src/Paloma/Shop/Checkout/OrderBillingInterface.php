<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\Common\AddressInterface;

interface OrderBillingInterface
{
    /**
     * @return OrderPaymentMethodInterface Payment method
     */
    function getPaymentMethod(): ?OrderPaymentMethodInterface;

    /**
     * @return AddressInterface|null Billing address
     */
    function getAddress(): ?AddressInterface;
}