<?php

namespace Paloma\Shop\Checkout;

class PaymentInstrument implements PaymentInstrumentInterface
{
    private $data;

    private $selected;

    public function __construct(array $data, bool $selected)
    {
        $this->data = $data;
        $this->selected = $selected;
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

    function isSelected(): bool
    {
        return $this->selected ?? false;
    }

    function isExpired(): bool
    {
        return $this->data['expired'] ?? false;
    }
}