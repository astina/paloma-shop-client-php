<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\Catalog\ImageInterface;

interface OrderItemInterface
{
    /**
     * @return string Order item SKU
     */
    function getSku(): string;

    /**
     * @return string Order item code (e.g. product item number)
     */
    function getCode(): string;

    /**
     * @return int Order item quantity
     */
    function getQuantity(): int;

    /**
     * @return string Order item title, e.g. product name
     */
    function getTitle(): string;

    /**
     * @return ImageInterface Optional: product image
     */
    function getImage(): ?ImageInterface;

    /**
     * @return string Price for one unit as formatted string including currency symbol (e.g. "CHF 12.80")
     */
    function getUnitPrice(): string;

    /**
     * @return string If the unit price is reduced, this property holds the original unit price.
     */
    function getOriginalPrice(): ?string;

    /**
     * @return string Price for this cart item as formatted string including currency symbol (e.g. "CHF 12.80")
     */
    function getItemPrice(): string;
}