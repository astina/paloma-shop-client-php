<?php

namespace Paloma\Shop\Common;

use Paloma\Shop\Utils\PriceUtils;

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
        return PriceUtils::format($this->currency, $this->amount);
    }

    function getCurrency(): string
    {
        return $this->currency;
    }
}