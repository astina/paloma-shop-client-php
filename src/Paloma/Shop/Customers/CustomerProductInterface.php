<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\Catalog\ProductVariantInterface;

interface CustomerProductInterface
{
    function getItemNumber(): string;

    function getSku(): string;

    function getTitle(): string;

    function getVariant(): ProductVariantInterface;

    /**
     * @return CustomerProductOrderInterface[]
     */
    function getOrders(): array;
}