<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\Common\Price;

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

    function getPrice(): string
    {
        $pricing = $this->data['pricing'];

        return (new Price($pricing['currency'], $pricing['grossPriceFormatted']))->getPrice();
    }

    function isSelected(): bool
    {
        return $this->selected;
    }

    function isFree(): bool
    {
        return $this->data['pricing']['grossPrice'] === 0;
    }
}