<?php

namespace Paloma\Shop\Checkout;

use DateTime;

class ShippingMethodOption implements ShippingMethodOptionInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getTargetDate(): DateTime
    {
        return DateTime::createFromFormat('Y-m-d', $this->data['targetDate']);
    }
}