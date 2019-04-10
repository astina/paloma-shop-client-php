<?php

namespace Paloma\Shop\Catalog;

interface SearchSuggestionsInterface
{
    /**
     * @return CategoryReferenceInterface[] List of suggested categories for a (partial) search term
     */
    function getCategories(): array;

    /**
     * @return ProductReferenceInterface[] List of suggested products for a (partial) search term
     */
    function getProducts(): array;
}