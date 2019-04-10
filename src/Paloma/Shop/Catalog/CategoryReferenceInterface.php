<?php

namespace Paloma\Shop\Catalog;

interface CategoryReferenceInterface
{
    /**
     * @return string Category code
     */
    function getCode(): string;

    /**
     * @return string Category name
     */
    function getName(): string;

    /**
     * @return string Category slug
     */
    function getSlug(): string;
}