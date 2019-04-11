<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\Common\Address;

class AddressUpdate extends Address implements AddressUpdateInterface
{
    private $addressType;

    public function __construct(string $addressType, ?string $title, ?string $firstName, ?string $lastName, ?string $company,
                                ?string $street, ?string $zipCode, ?string $city, ?string $country,
                                ?string $phoneNumber, ?string $emailAddress, ?string $remarks)
    {
        parent::__construct($title, $firstName, $lastName, $company, $street, $zipCode, $city, $country, $phoneNumber, $emailAddress, $remarks);
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