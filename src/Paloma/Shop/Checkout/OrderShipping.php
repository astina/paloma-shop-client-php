<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\Common\Address;
use Paloma\Shop\Common\AddressInterface;

class OrderShipping implements OrderShippingInterface
{
    private $data;

    public static function ofCartData(array $data)
    {
        return new OrderShipping([
            'address' => $data['shippingAddress'],
            'deliveryMethod' => $data['shippingMethod']['name'],
        ]);
    }

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getShippingMethod(): string
    {
        return $this->data['deliveryMethod'];
    }

    function getAddress(): ?AddressInterface
    {
        return $this->data['address'] === null
            ? null
            : Address::ofData($this->data['address']);
    }
}