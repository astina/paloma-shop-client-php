<?php

namespace Paloma\Shop\Customers;

use DateTime;
use Paloma\Shop\Common\Address;
use Paloma\Shop\Common\AddressInterface;

class Customer extends CustomerBasics implements CustomerInterface
{
    function getDateOfBirth(): ?DateTime
    {
        return isset($this->data['dateOfBirth'])
            ? DateTime::createFromFormat('Y-m-d', $this->data['dateOfBirth'])
            : null;
    }

    function getContactAddress(): AddressInterface
    {
        return Address::ofData($this->data['contactAddress']);
    }

    function getBillingAddress(): AddressInterface
    {
        return Address::ofData($this->data['billingAddress']);
    }

    function getShippingAddress(): AddressInterface
    {
        return Address::ofData($this->data['shippingAddress']);
    }
}