<?php

namespace Paloma\Shop\Catalog;

interface CategoryInterface extends CategoryReferenceInterface
{
    /**
     * @return string Category title (fallback: category name)
     */
    function getTitle(): ?string;

    /**
     * @return string Category description
     */
    function getDescription(): ?string;

    /**
     * @return string Category meta description for SEO
     */
    function getMetaDescription(): ?string;

    /**
     * @return string Parent category code
     */
    function getParentCategoryCode(): ?string;

    /**
     * @return CategoryInterface[] Optional: list of child categories
     */
    function getSubCategories(): ?array;

    /**
     * @return FilterAggregateInterface[] Optional: list of filter aggregates
     */
    function getFilterAggregates(): ?array;

    /**
     * @return CategoryReferenceInterface[]
     */
    function getAncestors(): array;

    function getCreated(): \DateTime;

    function getModified(): \DateTime;
}