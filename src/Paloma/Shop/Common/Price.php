<?php

namespace Paloma\Shop\Common;

class Price implements PriceInterface
{
    private $currency;

    private $amount;

    public function __construct(string $currency, string $amount)
    {
        $this->currency = $currency;
        $this->amount = $amount;
    }

    function getPrice(): string
    {
        return trim(sprintf('%s %s', $this->currency, $this->amount));
    }

    function getCurrency(): string
    {
        return $this->currency;
    }
}