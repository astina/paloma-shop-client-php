<?php

namespace Paloma\Shop\Catalog;

interface CategoryInterface
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

    /**
     * @return string Parent category code
     */
    function getParentCategoryCode(): ?string;

    /**
     * @return CategoryInterface[] Optional: list of child categories
     */
    function getSubCategories(): array;

    /**
     * @return FilterAggregateInterface[] Optional: list of filter aggregates
     */
    function getFilterAggregates(): array;
}