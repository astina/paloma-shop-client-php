<?php

namespace Paloma\Shop\Checkout;

interface OrderPaymentMethodInterface
{
    /**
     * @return string Payment method name
     */
    function getName(): string;

    /**
     * Payment methods with type 'electronic' require that the web shop performs a payment flow.
     *
     * @return string One of 'invoice', 'electronic'.
     */
    function getType(): string;
}