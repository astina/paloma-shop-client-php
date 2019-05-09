<?php

namespace Paloma\Shop\Catalog;

interface CategoryReferenceInterface
{
    /**
     * @return string Category code, unique within a channel
     */
    function getCode(): string;

    /**
     * @return string Category name
     */
    function getName(): string;

    /**
     * @return string SEO friendly name to be used for category URLs (together with the category code)
     */
    function getSlug(): string;
}