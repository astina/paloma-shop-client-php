<?php

namespace Paloma\Shop\Checkout;

use DateTime;

class OrderShippingMethod implements OrderShippingMethodInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getName(): string
    {
        return $this->data['name'];
    }

    function getTargetDate(): ?DateTime
    {
        return isset($this->data['targetDate'])
            ? DateTime::createFromFormat('Y-m-d', $this->data['targetDate'])
            : null;
    }
}