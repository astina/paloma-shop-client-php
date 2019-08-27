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
     * All products found over standard catalog API calls will have status 'active'.
     * Status 'inactive' means that the product is not available (anymore).
     *
     * @return string 'active' or 'inactive'
     */
    function getStatus(): string;

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
     * @return string Reduction from original base price to base price, formatted with percent sign (e.g. "-12 %")
     */
    function getReductionPercent(): ?string;

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
     * @return array Map of options accumulated from all variants.
     */
    function getOptions(): array;

    /**
     * The 'display' parameter knows the following values:
     * - 'product': Intended to be displayed on the product detail page
     * - 'category': Intended to be displayed on the product listing on a category page
     * - 'none': Not intended to be displayed, usually used for internal purposes / business logic
     *
     * @param string $display Product display value
     * @return array Map of attributes valid for all variants of this product
     */
    function getAttributes(string $display = 'product'): array;

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

    /**
     * @return CategoryReferenceInterface|null
     */
    function getMainCategory(): ?CategoryReferenceInterface;

    function getCreated(): ?\DateTime;

    function getModified(): ?\DateTime;
}