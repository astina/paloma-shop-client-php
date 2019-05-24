<?php

namespace Paloma\Shop\Common;

use Paloma\Shop\Utils\ArrayUtils;

class Address implements AddressInterface
{
    protected $title;

    protected $firstName;

    protected $lastName;

    protected $company;

    protected $street;

    protected $zipCode;

    protected $city;

    protected $country;

    protected $phoneNumber;

    protected $emailAddress;

    protected $remarks;

    public static function ofData($data)
    {
        if (ArrayUtils::allNull($data)) {
            return null;
        }

        return new Address(
            $data['title'] ?? null,
            $data['firstName'] ?? null,
            $data['lastName'] ?? null,
            $data['company'] ?? null,
            $data['street'] ?? null,
            $data['zipCode'] ?? null,
            $data['city'] ?? null,
            $data['country'] ?? null,
            $data['phoneNumber'] ?? null,
            $data['emailAddress'] ?? null,
            $data['remarks'] ?? null
        );
    }

    public static function toAddressArray(?AddressInterface $address)
    {
        if ($address === null) {
            return null;
        }

        return [
            'title' => $address->getTitle(),
            'firstName' => $address->getFirstName(),
            'lastName' => $address->getLastName(),
            'company' => $address->getCompany(),
            'street' => $address->getStreet(),
            'zipCode' => $address->getZipCode(),
            'city'=> $address->getCity(),
            'country' => $address->getCountry(),
            'phoneNumber' => $address->getPhoneNumber(),
            'emailAddress' => $address->getEmailAddress(),
            'remarks' => $address->getRemarks(),
        ];
    }

    public function __construct(?string $title = null, ?string $firstName = null, ?string $lastName = null, ?string $company = null,
                                ?string $street = null, ?string $zipCode = null, ?string $city = null, ?string $country = null,
                                ?string $phoneNumber = null, ?string $emailAddress = null, ?string $remarks = null)
    {
        $this->title = $title;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->company = $company;
        $this->street = $street;
        $this->zipCode = $zipCode;
        $this->city = $city;
        $this->country = $country;
        $this->phoneNumber = $phoneNumber;
        $this->emailAddress = $emailAddress;
        $this->remarks = $remarks;
    }

    function getTitle(): ?string
    {
        return $this->title;
    }

    function getFirstName(): ?string
    {
        return $this->firstName;
    }

    function getLastName(): ?string
    {
        return $this->lastName;
    }

    function getFullName(): ?string
    {
        return trim(sprintf('%s %s %s', $this->getCompany(), $this->getFirstName(), $this->getLastName()));
    }

    function getCompany(): ?string
    {
        return $this->company;
    }

    function getStreet(): ?string
    {
        return $this->street;
    }

    function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    function getCity(): ?string
    {
        return $this->city;
    }

    function getCountry(): ?string
    {
        return $this->country;
    }

    function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    function getRemarks(): ?string
    {
        return $this->remarks;
    }
}