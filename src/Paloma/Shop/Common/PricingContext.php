<?php

namespace Paloma\Shop\Common;

class PricingContext
{
    /**
     * @var string[]
     */
    private $priceGroups;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var \DateTime
     */
    private $date;

    public function __construct(array $priceGroups = null, $currency = null, $date = null)
    {
        $this->priceGroups = $priceGroups;
        $this->currency = $currency;
        $this->date = $date;
    }

    public function toArray(): array
    {
        return [
            'priceGroups' => $this->priceGroups,
            // TODO
            'date' => null,
            'currency' => $this->currency,
        ];
    }

    /**
     * @return string[]
     */
    public function getPriceGroups(): ?array
    {
        return $this->priceGroups;
    }

    /**
     * @return string
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }
}