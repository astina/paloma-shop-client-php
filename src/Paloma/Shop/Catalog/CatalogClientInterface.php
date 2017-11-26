<?php

namespace Paloma\Shop\Catalog;

interface CatalogClientInterface
{
    function search($search);

    function searchSuggestions($query);

    function product($itemNumber);

    function similarProducts($itemNumber);

    function recommendedProducts($itemNumber);

    function recommendations($order, $size = null);

    function categories($depth = null, $products = true);

    function category($code, $depth = null, $filterAggregates = null);

    function categoryFilters($code);
}
