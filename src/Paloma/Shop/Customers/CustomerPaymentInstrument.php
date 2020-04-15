<?php

namespace Paloma\Shop\Customers;

class CustomerPaymentInstrument implements CustomerPaymentInstrumentInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getId(): string
    {
        return $this->data['id'];
    }

    function getType(): string
    {
        return $this->data['type'] ?? $this->data['means'];
    }

    function getMaskedCardNumber(): string
    {
        return strtoupper($this->data['maskedCardNumber']);
    }

    function getExpirationMonth(): int
    {
        return (int) $this->data['expirationMonth'];
    }

    function getExpirationYear(): int
    {
        $expirationYear = (int)$this->data['expirationYear'];

        return $expirationYear < 2000
            ? $expirationYear + 2000
            : $expirationYear;
    }

    function isExpired(): bool
    {
        return $this->data['expired'];
    }
}