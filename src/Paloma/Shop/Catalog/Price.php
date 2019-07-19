<?php

namespace Paloma\Shop\Catalog;

class Price implements PriceInterface
{
    private $price;

    private $taxRate;

    private $taxIncluded;

    private $currency;

    public function __construct(array $data)
    {
        // old 'pricing' object
        if (isset($data['grossPriceFormatted'])) {

            $this->price = sprintf('%s %s',
                $this->data['currency'],
                $this->isTaxIncluded()
                    ? $this->data['grossPriceFormatted']
                    : $this->data['netPriceFormatted']);

            $this->taxRate = $this->data['taxes']['vat']['rateFormatted'];
            $this->taxIncluded = true;
            $this->currency = $data['currency'];

        // new 'price' object
        } else if (isset($data['unitPrice'])) {

            $this->price = $data['unitPrice'];
            $this->taxRate = $data['tax'];
            $this->taxIncluded = $data['taxIncluded'];
            $this->currency = $data['currency'];

        } else {
            throw new \InvalidArgumentException('Invalid price data');
        }
    }

    function getPrice(): string
    {
        return $this->price;
    }

    function getTaxRate(): string
    {
        return $this->taxRate;
    }

    function isTaxIncluded(): bool
    {
        return $this->taxIncluded;
    }

    function getCurrency(): string
    {
        return $this->currency;
    }
}