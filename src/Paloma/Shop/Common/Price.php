<?php

namespace Paloma\Shop\Common;

use Paloma\Shop\Utils\PriceUtils;

class Price implements PriceInterface
{
    private $currency;

    private $amount;

    private $unit;

    public function __construct(string $currency, string $amount, ?string $unit = null)
    {
        $this->currency = $currency;
        $this->amount = $amount;
        $this->unit = $unit;
    }

    function getPrice(): string
    {
        return PriceUtils::format($this->currency, $this->amount);
    }

    function getCurrency(): string
    {
        return $this->currency;
    }

    function getUnit(): ?string
    {
        return $this->unit;
    }
}