<?php

namespace Paloma\Shop\Catalog;

interface CatalogClientInterface
{
    function categories($country, $language, $depth = null, $products = true);

    function category($country, $language, $code, $depth = null, $filterAggregates = null);

    function categoryFilters($country, $language, $code);

    function product($country, $language, $itemNumber);

    function recommendedProducts($country, $language, $itemNumber);

    function similarProducts($country, $language, $itemNumber);

    function recommendations($country, $language, $order, $size = null);

    function search($country, $language, $searchRequest);

    function searchSuggestions($country, $language, $partial);
}