<?php

namespace Paloma\Shop\Catalog;

class Price implements PriceInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getPrice(): string
    {
        return sprintf('%s %s',
            $this->data['currency'],
            $this->isTaxIncluded()
                ? $this->data['grossPriceFormatted']
                : $this->data['netPriceFormatted']);
    }

    function getTaxRate(): string
    {
        return $this->data['taxes']['vat']['rateFormatted'];
    }

    function isTaxIncluded(): bool
    {
        return true; // TODO
    }

    function getCurrency(): string
    {
        return $this->data['currency'];
    }
}