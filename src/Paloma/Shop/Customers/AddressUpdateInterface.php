<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\Common\AddressInterface;

interface AddressUpdateInterface extends AddressInterface
{
    /**
     * @return string One of 'billing', 'shipping', 'contact'
     */
    function getAddressType(): string;
}