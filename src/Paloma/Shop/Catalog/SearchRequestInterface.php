<?php

namespace Paloma\Shop\Catalog;

interface SearchRequestInterface
{
    /**
     * @return string Category code: if set, only products assigned to this category are returned
     */
    function getCategory(): ?string;

    /**
     * @return string Search keywords
     */
    function getQuery(): ?string;

    /**
     * @return SearchFilterInterface[] Search filters, will be applied using AND operations.
     */
    function getFilters(): array;

    /**
     * @return bool Whether to include aggregated option and attribute values over all search results.
     */
    function includeFilterAggregates(): bool;

    /**
     * @return int Page number, starting from 0
     */
    function getPage(): int;

    /**
     * @return int Results page size
     */
    function getSize(): int;

    /**
     * @return string Accepts 'name', 'price', 'position', 'relevance' as well as any attribute in the
     *                form attribute.[type]. By default, sorting is done by 'relevance' except when a
     *                category code is specified in which case the default is 'position'.
     */
    function getSort(): string;

    /**
     * @return bool If results should be returned in descending order
     */
    function isOrderDesc(): bool;
}