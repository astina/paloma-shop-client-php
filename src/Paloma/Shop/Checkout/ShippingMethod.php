<?php

namespace Paloma\Shop\Checkout;

class ShippingMethod implements ShippingMethodInterface
{
    private $data;

    private $selected;

    public static function ofDataAndOrder(array $data, array $order)
    {
        return new ShippingMethod(
            $data,
            isset($order['shippingMethod']['name'])
            && $order['shippingMethod']['name'] === $data['name']
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

    function isSelected(): bool
    {
        return $this->selected;
    }
}