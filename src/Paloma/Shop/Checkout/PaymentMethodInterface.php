<?php

namespace Paloma\Shop\Checkout;

interface PaymentMethodInterface
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

    /**
     * @return string|null Only for type 'electronic': The payment provider that the web shop
     *                     should use to perform the payment flow.
     *                     One of 'datatrans', 'saferpay', 'computop', 'paypal', 'swiss_postfinance'.
     */
    function getProvider(): ?string;

    /**
     * @return bool True, if this payment method is set for the current order
     */
    function isSelected(): bool;
}