<?php

namespace Paloma\Shop\Checkout;

use DateTime;

class ShippingMethodOptions implements ShippingMethodOptionsInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getValidUntil(): ?DateTime
    {
        return isset($this->data['validUntil'])
            ? new DateTime($this->data['validUntil'])
            : null;
    }

    /**
     * @return ShippingMethodOptionInterface[]
     */
    function getDelivery(): array
    {
        return array_map(function($elem) {
            return new ShippingMethodOption($elem);
        }, ($this->data['delivery'] ?? []));
    }
}