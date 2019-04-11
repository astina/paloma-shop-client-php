<?php

namespace Paloma\Shop\Checkout;

interface PaymentInitParametersInterface
{
    function getSuccessUrl(): string;

    function getCancelUrl(): string;

    function getErrorUrl(): string;
}