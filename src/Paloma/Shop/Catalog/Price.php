<?php

namespace Paloma\Shop\Catalog;

class Price implements PriceInterface
{
    private $price;

    private $taxRate;

    private $taxIncluded;

    private $currency;

    private $unit = null;

    public function __construct(array $data)
    {
        // old 'pricing' object
        if (isset($data['grossPriceFormatted'])) {

            $this->price = sprintf('%s %s',
                $data['currency'],
                $data['grossPriceFormatted']);

            $this->taxRate = $data['taxes']['vat']['rateFormatted'] ?? null;
            $this->taxIncluded = $data['taxes']['taxInclusion'] ?? true;

            $this->currency = $data['currency'];

            // new 'price' object
        } else if (isset($data['unitPrice'])) {

            $this->price = sprintf('%s %s',
                $data['currency'],
                $data['unitPrice']);

            $this->taxRate = $data['tax'];
            $this->taxIncluded = $data['taxIncluded'];
            $this->currency = $data['currency'];
            $this->unit = $data['unit'];

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

    function getUnit(): ?string
    {
        return $this->unit === 'piece'
            ? null
            : $this->unit;
    }
}