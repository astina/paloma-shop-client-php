<?php

namespace Paloma\Shop\Catalog;

/**
 * A product can have multiple variants. A variant can have its own attributes, price and images.
 * If applicable, each variant has a list of options which define the variant (e.g. "size" or "color").
 */
interface ProductVariantInterface
{
    /**
     * @return string Variant SKU (unique within a channel)
     */
    function getSku(): string;

    /**
     * @return string Variant name
     */
    function getName(): string;

    /**
     * @return string Price for one unit of this variant as formatted string including currency symbol (e.g. "CHF 12.80")
     */
    function getPrice(): string;

    /**
     * @return string If the price is reduced, this property holds the original price.
     */
    function getOriginalPrice(): ?string;

    /**
     * @return string Tax rate, formatted with percent sign (e.g. "7.7 %")
     */
    function getTaxRate(): string;

    /**
     * @return bool True, if price is including tax
     */
    function isTaxIncluded(): bool;

    /**
     * @return ProductVariantOptionInterface[] List of options defining this variant (e.g. color=blue, size=M)
     */
    function getOptions(): array;

    /**
     * @return array Map of attributes specific to this variant
     */
    function getAttributes(): array;

    /**
     * @param string $type Product attribute type
     * @return ProductAttributeInterface Attribute with the given type (if present)
     */
    function getAttribute(string $type): ?ProductAttributeInterface;

    /**
     * @return ImageInterface[] List of images specific to this variant
     */
    function getImages(): array;

    /**
     * @return ImageInterface The first image of getImages()
     */
    function getFirstImage(): ?ImageInterface;
}