<?php

namespace Paloma\Shop\Catalog;

interface CatalogClientInterface
{
    function search($search);

    function searchSuggestions($query);

    function product($itemNumber, array $context = null);

    function similarProducts($itemNumber, array $context = null);

    function recommendedProducts($itemNumber, array $context = null);

    function recommendations($order, $size = null, array $context = null);

    function categories($depth = null, $products = true);

    function category($code, $depth = null, $filterAggregates = null);

    function categoryFilters($code);

    function listBySkus(array $skus, $omitOtherVariants = false, $includeInactiveProducts = false, array $context = null);
}
