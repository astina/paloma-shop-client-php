<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\Checkout\OrderAdjustmentInterface;
use Paloma\Shop\Common\Price;

class OrderAdjustment implements OrderAdjustmentInterface
{
    private $data;

    private $currency;

    public function __construct(array $data, string $currency)
    {
        $this->data = $data;
        $this->currency = $currency;
    }

    function getDescription(): string
    {
        return $this->data['name'];
    }

    function getPrice(): string
    {
        return (new Price($this->currency, $this->data['grossItemTotal'] ?? $this->data['linePrice']))->getPrice();
    }
}