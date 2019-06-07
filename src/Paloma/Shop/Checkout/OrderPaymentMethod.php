<?php

namespace Paloma\Shop\Checkout;

class OrderPaymentMethod implements OrderPaymentMethodInterface
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

    function getType(): string
    {
        return $this->data['type'] ?? '';
    }
}