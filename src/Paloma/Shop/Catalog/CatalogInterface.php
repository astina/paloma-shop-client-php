<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\Api\CartIsEmpty;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\CategoryNotFound;
use Paloma\Shop\Error\InvalidInput;
use Paloma\Shop\Error\ProductNotFound;

interface CatalogInterface
{
    /**
     * There are three ways to search for products, all of which can be used in conjunction.
     * 1. Filter by category code: Only products listed in the given category are found.
     * 2. Search query: The query parameter is intended for search field scenarios.
     *    Any input the user puts into a search field can be used here.
     * 3. Filters: Search results are filtered by the given property and values.
     *
     * @param SearchRequestInterface $searchRequest
     * @return ProductPageInterface
     * @throws BackendUnavailable
     * @throws InvalidInput
     */
    function search(SearchRequestInterface $searchRequest): ProductPageInterface;

    /**
     * Returns category and product suggestions for a search query. This is intended to be used in "search as you type" scenarios.
     *
     * @param string $query
     * @return SearchSuggestionsInterface
     * @throws BackendUnavailable
     */
    function getSearchSuggestions(string $query): SearchSuggestionsInterface;

    /**
     * @param string $itemNumber
     * @return ProductInterface The product for the given item number
     * @throws ProductNotFound
     * @throws BackendUnavailable
     */
    function getProduct(string $itemNumber): ProductInterface;

    /**
     * @param string $itemNumber
     * @return ProductPageInterface Products which are similar to the product for the given item number
     * @throws ProductNotFound
     * @throws BackendUnavailable
     */
    function getSimilarProducts(string $itemNumber): ProductPageInterface;

    /**
     * @param string $itemNumber
     * @return ProductPageInterface Product recommendations for a product item number
     * @throws ProductNotFound
     * @throws BackendUnavailable
     */
    function getRecommendedProducts(string $itemNumber): ProductPageInterface;

    /**
     * @param int $size Maximum number of results (default: 5)
     * @return ProductPageInterface Product recommendations for the contents of the current shopping cart
     * @throws BackendUnavailable
     */
    function getProductsForCart(int $size = 5): ProductPageInterface;

    /**
     * @param int $depth Levels of category descendants to be included (default: 0)
     * @return CategoryInterface[]
     * @throws BackendUnavailable
     */
    function getCategories(int $depth = 0): array;

    /**
     * @param string $categoryCode Category code
     * @param int $depth Levels of category descendants to be included (default: 0)
     * @param bool $includeFilterAggregates Whether to include aggregated option and attribute values over all category products
     * @return CategoryInterface
     * @throws CategoryNotFound
     * @throws BackendUnavailable
     */
    function getCategory(string $categoryCode, int $depth = 0, bool $includeFilterAggregates = false): CategoryInterface;
}