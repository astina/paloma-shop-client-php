<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\Catalog\ImageInterface;
use Paloma\Shop\Catalog\ProductInterface;
use Paloma\Shop\Catalog\ProductVariantInterface;

interface CartItemInterface
{
    /**
     * @return string Cart item ID
     */
    function getId(): string;

    /**
     * @return int Cart item quantity
     */
    function getQuantity(): int;

    /**
     * @return int The maximum quantity (stock) available for this SKU
     */
    function getAvailableQuantity(): int;

    /**
     * @return string Cart item title, e.g. product name
     */
    function getTitle(): string;

    /**
     * @return string Optional: cart item description
     */
    function getDescription(): ?string;

    /**
     * @return string Cart item SKU
     */
    function getSku(): string;

    /**
     * @return string Cart item product item number
     */
    function getItemNumber(): string;

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

    /**
     * @return ProductInterface|null
     */
    function getProduct(): ?ProductInterface;

    /**
     * @return ProductVariantInterface|null
     */
    function getProductVariant(): ?ProductVariantInterface;
}