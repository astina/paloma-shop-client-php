<?php

namespace Paloma\Shop\Common;

interface PriceInterface
{
    /**
     * @return string Formatted price including currency symbol (e.g. CHF 129.90)
     */
    function getPrice(): string;

    /**
     * @return string Currency symbol
     */
    function getCurrency(): string;

    /**
     * @return string Price unit (localized)
     */
    function getUnit(): ?string;
}