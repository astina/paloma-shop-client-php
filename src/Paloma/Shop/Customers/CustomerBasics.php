<?php

namespace Paloma\Shop\Customers;

class CustomerBasics implements CustomerBasicsInterface
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getId(): ?string
    {
        if (isset($this->data['customerId'])) {
            return $this->data['customerId'];
        }

        return $this->data['id'] ?? null;
    }

    function getEmailAddress(): ?string
    {
        return $this->data['emailAddress'];
    }

    function getCustomerNumber(): ?string
    {
        return $this->data['customerNumber'];
    }

    function getLocale(): string
    {
        return $this->data['locale'];
    }

    function getFirstName(): ?string
    {
        return $this->data['firstName'];
    }

    function getLastName(): ?string
    {
        return $this->data['lastName'];
    }

    function getCompany(): ?string
    {
        return $this->data['company'] ?? null;
    }

    function getGender(): string
    {
        return $this->data['gender'];
    }
}