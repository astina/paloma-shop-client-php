<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\BaseClient;
use Paloma\Shop\PalomaProfiler;
use Psr\Log\LoggerInterface;

class CatalogClient extends BaseClient implements CatalogClientInterface
{
    public function __construct($baseUrl, $apiKey, $channel, LoggerInterface $logger = null, PalomaProfiler $profiler = null)
    {
       parent::__construct($baseUrl, $apiKey, $channel, $logger, $profiler);
    }

    public function search($locale, $search)
    {
        return $this->post($this->channel . '/' . $locale . '/search', null, $search);
    }

    function searchSuggestions($locale, $query)
    {
        return $this->get($this->channel . '/' . $locale . '/search/suggestions', ['query' => $query]);
    }


    public function product($locale, $itemNumber)
    {
        return $this->get($this->channel . '/' . $locale . '/products/' . $itemNumber);
    }

    function similarProducts($locale, $itemNumber)
    {
        return $this->get($this->channel . '/' . $locale . '/products/' . $itemNumber . '/similar');
    }

    function recommendedProducts($locale, $itemNumber)
    {
        return $this->get($this->channel . '/' . $locale . '/products/' . $itemNumber . '/recommended');
    }

    function recommendations($locale, $order, $size = null)
    {
        return $this->post($this->channel . '/' . $locale . '/recommendations', $size ? ['size' => $size] : null, $order);
    }


    public function categories($locale, $depth = null, $products = true)
    {
        $query = ['products' => ($products ? 'true' : 'false')];
        if ($depth) {
            $query['depth'] = $depth;
        }

        return $this->get($this->channel . '/' . $locale . '/categories', $query);
    }

    public function category($locale, $code, $depth = null, $filterAggregates = null)
    {
        $query = [];
        if ($depth) {
            $query['depth'] = $depth;
        }
        if ($filterAggregates) {
            $query['filter-aggregates'] = $filterAggregates;
        }
        return $this->get($this->channel . '/' . $locale . '/categories/' . $code, count($query) > 0 ? $query : null);
    }

    public function categoryFilters($locale, $code)
    {
        return $this->get($this->channel . '/' . $locale . '/categories/' . $code . '/filter-aggregates');
    }




}