<?php

namespace Paloma\Shop\Checkout;

class Address implements AddressInterface
{
    private $title;

    private $firstName;

    private $lastName;

    private $company;

    private $street;

    private $zipCode;

    private $city;

    private $country;

    private $phoneNumber;

    private $emailAddress;

    private $remarks;

    public static function ofData($data)
    {
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

    public function __construct($title, $firstName, $lastName, $company, $street, $zipCode, $city, $country, $phoneNumber, $emailAddress, $remarks)
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

    function getCountry(): string
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