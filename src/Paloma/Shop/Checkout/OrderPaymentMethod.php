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

    /**
     * @return bool Returns true if this payment method requires payment during the checkout process.
     */
    function isRequiresPaymentDuringCheckout(): bool
    {
        return $this->getType() === 'electronic';
    }
}