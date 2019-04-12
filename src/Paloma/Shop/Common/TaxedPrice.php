<?php

namespace Paloma\Shop\Common;

class TaxedPrice extends Price implements TaxedPriceInterface
{
    private $taxRate;

    private $taxIncluded;

    public function __construct(string $currency, string $amount, string $taxRate, bool $taxIncluded)
    {
        parent::__construct($currency, $amount);
        $this->taxRate = $taxRate;
        $this->taxIncluded = $taxIncluded;
    }

    function getTaxRate(): string
    {
        return $this->taxRate;
    }

    function isTaxIncluded(): bool
    {
        return $this->taxIncluded;
    }
}