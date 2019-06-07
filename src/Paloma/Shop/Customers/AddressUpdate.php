<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\Common\Address;

class AddressUpdate extends Address implements AddressUpdateInterface
{
    private $addressType;

    public function __construct(string $addressType,
                                string $title = null, string $titleCode = null, string $firstName = null, string $lastName = null, string $company = null,
                                string $street = null, string $zipCode = null, string $city = null, string $country = null,
                                string $phoneNumber = null, string $emailAddress = null, string $remarks = null)
    {
        parent::__construct($title, $titleCode, $firstName, $lastName, $company, $street, $zipCode, $city, $country, $phoneNumber, $emailAddress, $remarks);
        $this->addressType = $addressType;
    }

    /**
     * @return string One of 'billing', 'shipping', 'contact'
     */
    function getAddressType(): string
    {
        return $this->addressType;
    }
}