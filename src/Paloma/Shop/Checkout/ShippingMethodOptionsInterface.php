<?php

namespace Paloma\Shop\Checkout;

use DateTime;

interface ShippingMethodOptionsInterface
{
    /**
     * @return DateTime Date indicating how long the provided options are valid.
     */
    function getValidUntil(): ?DateTime;

    /**
     * @return ShippingMethodOptionInterface[]
     */
    function getDelivery(): array;
}