<?php

namespace Paloma\Shop\Checkout;

interface OrderAdjustmentInterface
{
    /**
     * @return string Adjustment description
     */
    function getDescription(): string;

    /**
     * @return string Adjustment price as formatted string including currency symbol (e.g. "CHF 12.80")
     */
    function getPrice(): string;
}