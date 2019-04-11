<?php

namespace Paloma\Shop\Checkout;

interface PaymentDraftInterface
{
    /**
     * @return string Payment reference
     */
    function getReference(): string;

    /**
     * @return string Currency code
     */
    function getCurrency(): string;

    /**
     * @return string Payment amount
     */
    function getAmount(): string;

    /**
     * @return array Parameters (key-value pairs) needed to start the payment process.
     *               Those parameters are depending on the selected payment provider.
     */
    function getProviderParams(): array;
}