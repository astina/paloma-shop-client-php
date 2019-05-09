<?php

namespace Paloma\Shop\Catalog;

/**
 * Products are the main entities of the catalog. A Product object contains basic information about the product,
 * its attributes, price, availability, images and variants.
 */
interface ProductInterface
{
    /**
     * @return string Product item number (unique within a channel)
     */
    function getItemNumber(): string;

    /**
     * @return string Product name
     */
    function getName(): string;

    /**
     * @return string SEO friendly name to be used for product URLs (together with the product item number)
     */
    function getSlug(): string;

    /**
     * @return string Product description
     */
    function getDescription(): ?string;

    /**
     * @return string Short version of the product description
     */
    function getShortDescription(): ?string;

    /**
     * @return string Price for one unit of the first variant of this product as formatted string including currency symbol (e.g. "CHF 12.80")
     */
    function getBasePrice(): string;

    /**
     * @return string If the base price is reduced, this property holds the original price.
     */
    function getOriginalBasePrice(): ?string;

    /**
     * @return string Tax rate, formatted with percent sign (e.g. "7.7 %")
     */
    function getTaxRate(): string;

    /**
     * @return bool True, if price is including tax
     */
    function isTaxIncluded(): bool;

    /**
     * @return ProductVariantInterface[]
     */
    function getVariants(): array;

    /**
     * @return array Map of attributes valid for all variants of this product
     */
    function getAttributes(): array;

    /**
     * @param string $type Product attribute type
     * @return ProductAttributeInterface Attribute with the given type (if present)
     */
    function getAttribute(string $type): ?ProductAttributeInterface;

    /**
     * @return ImageInterface[] List of product images
     */
    function getImages(): array;

    /**
     * @return ImageInterface The first image of getImages()
     */
    function getFirstImage(): ?ImageInterface;

    /**
     * @return CategoryReferenceInterface[]
     */
    function getCategories(): array;
}