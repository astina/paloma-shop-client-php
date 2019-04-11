<?php

namespace Paloma\Shop\Checkout;

class PaymentInitParameters implements PaymentInitParametersInterface
{
    private $successUrl;

    private $cancelUrl;

    private $errorUrl;

    public function __construct(string $successUrl, string $cancelUrl, string $errorUrl)
    {
        $this->successUrl = $successUrl;
        $this->cancelUrl = $cancelUrl;
        $this->errorUrl = $errorUrl;
    }


    function getSuccessUrl(): string
    {
        return $this->successUrl;
    }

    function getCancelUrl(): string
    {
        return $this->cancelUrl;
    }

    function getErrorUrl(): string
    {
        return $this->errorUrl;
    }
}