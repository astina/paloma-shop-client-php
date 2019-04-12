<?php

namespace Paloma\Shop\Common;

interface TaxedPriceInterface extends PriceInterface
{
    function getTaxRate(): string;

    function isTaxIncluded(): bool;
}