<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\Common\Address;
use Paloma\Shop\Common\AddressInterface;

class OrderBilling implements OrderBillingInterface
{
    private $data;

    public static function ofCartData(array $data)
    {
        return new OrderBilling([
            'address' => $data['billingAddress'],
            'paymentMethod' => $data['paymentMethod'],
        ]);
    }

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getPaymentMethod(): OrderPaymentMethodInterface
    {
        return isset($this->data['paymentMethod']['name'])
            ? new OrderPaymentMethod($this->data['paymentMethod'])
            : null;
    }

    function getAddress(): ?AddressInterface
    {
        return $this->data['address'] === null
            ? null
            : Address::ofData($this->data['address']);
    }
}