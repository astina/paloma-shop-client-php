<?php

namespace Paloma\Shop\Catalog;

interface PriceInterface
{
    function getPrice(): string;

    function getTaxRate(): string;

    function isTaxIncluded(): bool;
}