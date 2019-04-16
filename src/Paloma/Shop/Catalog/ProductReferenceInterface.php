<?php

namespace Paloma\Shop\Catalog;

interface ProductReferenceInterface
{
    /**
     * @return string Product item number
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
     * @return CategoryReferenceInterface One of the categories this product is in
     */
    function getCategory(): ?CategoryReferenceInterface;

    /**
     * @return CategoryReferenceInterface Top-level ancestor of the category (from getCategory())
     */
    function getMainCategory(): ?CategoryReferenceInterface;
}