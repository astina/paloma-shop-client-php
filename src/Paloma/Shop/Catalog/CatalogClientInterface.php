<?php

namespace Paloma\Shop\Catalog;

interface CatalogClientInterface
{
    function categories($locale, $depth = null, $products = true);

    function category($locale, $code, $depth = null, $filterAggregates = null);

    function categoryFilters($locale, $code);

    function product($locale, $itemNumber);

    function recommendedProducts($locale, $itemNumber);

    function similarProducts($locale, $itemNumber);

    function recommendations($locale, $order, $size = null);

    function search($locale, $searchRequest);

    function searchSuggestions($locale, $query);

    //TODO av: brands (see swagger doc)
}