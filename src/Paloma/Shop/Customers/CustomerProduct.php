<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\Catalog\ProductVariant;
use Paloma\Shop\Catalog\ProductVariantInterface;

class CustomerProduct implements CustomerProductInterface
{
    private $data;

    function __construct(array $data)
    {
        $this->data = $data;
    }

    function getItemNumber(): string
    {
        return $this->data['itemNumber'];
    }

    function getSku(): string
    {
        return $this->data['sku'];
    }

    function getTitle(): string
    {
        return $this->data['title'] ?? $this->data['variant']['name'];
    }

    function getVariant(): ProductVariantInterface
    {
        return new ProductVariant($this->data['variant']);
    }

    /**
     * @return CustomerProductOrderInterface[]
     */
    function getOrders(): array
    {
        return array_map(function($elem) {
            return new CustomerProductOrder($elem);
        }, $this->data['orders']);
    }
}