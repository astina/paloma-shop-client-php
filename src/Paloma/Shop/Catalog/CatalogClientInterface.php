<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\FileResponse;

interface CatalogClientInterface
{
    function search($search);

    function searchFilterAggregates();

    function searchSuggestions($query);

    function product($itemNumber);

    function similarProducts($itemNumber);

    function recommendedProducts($itemNumber);

    function recommendations($order, $size = null);

    function categories($depth = null, $products = true);

    function category($code, $depth = null, $filterAggregates = null);

    function categoryFilters($code);

    function exportSearch($format, $body, $locale = null);

    function exportProducts($format, $body, $locale = null);

    function exportStatus($processId, $locale = null);

    /**
     * @return FileResponse
     */
    function exportDownload($processId, $locale = null);
}
