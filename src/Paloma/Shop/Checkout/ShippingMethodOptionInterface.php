<?php

namespace Paloma\Shop\Checkout;

use DateTime;

interface ShippingMethodOptionInterface
{
    function getTargetDate(): DateTime;
}