<?php

namespace Paloma\Shop\Checkout;

use DateTime;

interface OrderShippingMethodInterface
{
    /**
     * @return string Shipping method name
     */
    function getName(): ?string;

    /**
     * @return DateTime|null Targeted delivery date (optional)
     */
    function getTargetDate(): ?DateTime;
}