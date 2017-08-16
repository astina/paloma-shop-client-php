<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\BaseClient;

class CatalogClient extends BaseClient implements CatalogClientInterface
{
    public function __construct($baseUrl, $apiKey, $debug = false)
    {
       parent::__construct($baseUrl, $apiKey, $debug);
    }

    public function categories($country, $language, $depth)
    {
        return $this->get($country . '/' . $language . '/categories', ['depth' => $depth]);
    }

    public function category($country, $language, $code, $depth, $filterAggregates)
    {
        return $this->get($country . '/' . $language . '/categories/' . $code, ['depth' => $depth, 'filter-aggregates' => $filterAggregates]);
    }

    public function categoryFilters($country, $language, $code)
    {
        return $this->get($country . '/' . $language . '/categories/' . $code . '/filter-aggregates');
    }

    public function product($country, $language, $itemNumber)
    {
        return $this->get($country . '/' . $language . '/products/' . $itemNumber);
    }

    function recommendedProducts($country, $language, $itemNumber)
    {
        return $this->get($country . '/' . $language . '/products/' . $itemNumber . '/recommended');
    }

    function similarProducts($country, $language, $itemNumber)
    {
        return $this->get($country . '/' . $language . '/products/' . $itemNumber . '/similar');
    }

    public function search($country, $language, $searchRequest)
    {
        return $this->post($country . '/' . $language . '/search', null, $searchRequest);
    }

    function searchSuggestions($country, $language, $partial)
    {
        return $this->get($country . '/' . $language . '/search/suggestions', ['query' => $partial]);
    }

    function recommendations($country, $language, $order, $size)
    {
        return $this->post($country . '/' . $language . '/recommendations', ['size' => $size], $order);
    }
}