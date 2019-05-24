<?php

namespace Paloma\Shop\Customers;

use DateTime;
use Paloma\Shop\Common\AddressInterface;

interface CustomerInterface extends CustomerBasicsInterface
{
    /**
     * @return DateTime|null Date without time component
     */
    function getDateOfBirth(): ?DateTime;

    /**
     * @return AddressInterface The customer's default contact address
     */
    function getContactAddress(): ?AddressInterface;

    /**
     * @return AddressInterface The customer's default billing address
     */
    function getBillingAddress(): ?AddressInterface;

    /**
     * @return AddressInterface The customer's default shipping address
     */
    function getShippingAddress(): ?AddressInterface;
}