<?php

namespace Paloma\Shop\Customers;

class CustomerUser implements CustomerUserInterface
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getId(): string
    {
        return $this->data['id'];
    }

    public function getUsername(): string
    {
        return $this->data['username'];
    }

    public function getEmailAddress(): string
    {
        return $this->data['emailAddress'];
    }

    public function isEnabled(): string
    {
        return !!$this->data['enabled'];
    }

    public function isConfirmed(): string
    {
        return !!$this->data['confirmed'];
    }

    public function getFirstName(): ?string
    {
        return $this->data['firstName'];
    }

    public function getLastName(): ?string
    {
        return $this->data['lastName'];
    }

    public function getLocale(): string
    {
        return $this->data['locale'];
    }

    public function getFullName(): string
    {
        $fullName = trim($this->getFirstName() . ' ' . $this->getLastName());
        return $fullName ?: $this->getUsername();
    }

    public function getTimeZone(): string
    {
        return $this->data['timeZone'];
    }
}