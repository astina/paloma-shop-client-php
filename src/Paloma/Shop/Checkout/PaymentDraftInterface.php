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
     * @return string Name of the payment provider (e.g. 'datatrans')
     */
    function getProvider(): string;

    /**
     * @return array Parameters (key-value pairs) needed to start the payment process.
     *               Those parameters are depending on the selected payment provider.
     */
    function getProviderParams(): array;

    /**
     * @return string|null URL where the user needs to be redirected to in order to complete this payment.
     */
    function getPaymentUrl(): ?string;
}