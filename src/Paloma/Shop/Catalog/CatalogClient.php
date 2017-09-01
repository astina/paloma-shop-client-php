<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\BaseClient;
use Paloma\Shop\PalomaProfiler;
use Psr\Log\LoggerInterface;

class CatalogClient extends BaseClient implements CatalogClientInterface
{
    public function __construct($baseUrl, $apiKey, LoggerInterface $logger = null, PalomaProfiler $profiler = null)
    {
       parent::__construct($baseUrl, $apiKey, $logger, $profiler);
    }

    public function categories($country, $language, $depth = null, $products = true)
    {
        $query = ['products' => ($products ? 'true' : 'false')];
        if ($depth) {
            $query['depth'] = $depth;
        }

        return $this->get($country . '/' . $language . '/categories', $query);
    }

    public function category($country, $language, $code, $depth = null, $filterAggregates = null)
    {
        $query = [];
        if ($depth) {
            $query['depth'] = $depth;
        }
        if ($filterAggregates) {
            $query['filter-aggregates'] = $filterAggregates;
        }
        return $this->get($country . '/' . $language . '/categories/' . $code, count($query) > 0 ? $query : null);
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

    function recommendations($country, $language, $order, $size = null)
    {
        return $this->post($country . '/' . $language . '/recommendations', $size ? ['size' => $size] : null, $order);
    }
}