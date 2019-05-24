<?php

namespace Paloma\Shop\Checkout;

class PaymentMethod implements PaymentMethodInterface
{
    private $data;

    private $selected;

    public static function ofDataAndOrder(array $data, array $order): PaymentMethod
    {
        return new PaymentMethod(
            $data,
            isset($order['paymentMethod']['name'])
            && $order['paymentMethod']['name'] === $data['name']
        );
    }

    public function __construct(array $data, bool $selected = false)
    {
        $this->data = $data;
        $this->selected = $selected;
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

    function isSelected(): bool
    {
        return $this->selected;
    }
}