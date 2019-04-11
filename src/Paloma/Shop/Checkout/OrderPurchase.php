<?php

namespace Paloma\Shop\Checkout;

class OrderPurchase implements OrderPurchaseInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getOrderNumber(): string
    {
        return $this->data['orderNumber'];
    }
}