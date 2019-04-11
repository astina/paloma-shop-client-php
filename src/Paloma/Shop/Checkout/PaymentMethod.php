<?php

namespace Paloma\Shop\Checkout;

class PaymentMethod implements PaymentMethodInterface
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
        return $this->data['type'];
    }

    function getProvider(): ?string
    {
        return isset($this->data['provider'])
            ? $this->data['provider']
            : null;
    }
}