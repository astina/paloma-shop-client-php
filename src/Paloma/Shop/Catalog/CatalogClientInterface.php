<?php

namespace Paloma\Shop\Catalog;

interface CatalogClientInterface
{
    function search($locale, $search);

    function searchSuggestions($locale, $query);

    function product($locale, $itemNumber);

    function similarProducts($locale, $itemNumber);

    function recommendedProducts($locale, $itemNumber);

    function recommendations($locale, $order, $size = null);

    function categories($locale, $depth = null, $products = true);

    function category($locale, $code, $depth = null, $filterAggregates = null);

    function categoryFilters($locale, $code);
}