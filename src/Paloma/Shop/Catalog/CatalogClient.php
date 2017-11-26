<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\BaseClient;
use Paloma\Shop\PalomaProfiler;
use Psr\Log\LoggerInterface;

class CatalogClient extends BaseClient implements CatalogClientInterface
{
    public function __construct($baseUrl, $apiKey, $channel, $locale, LoggerInterface $logger = null, PalomaProfiler $profiler = null)
    {
        parent::__construct($baseUrl, $apiKey, $channel, $locale, $logger, $profiler);
    }

    public function search($search)
    {
        return $this->post($this->channel . '/' . $this->locale . '/search', null, $search);
    }

    function searchSuggestions($query)
    {
        return $this->get($this->channel . '/' . $this->locale . '/search/suggestions', ['query' => $query]);
    }

    public function product($itemNumber)
    {
        return $this->get($this->channel . '/' . $this->locale . '/products/' . $itemNumber);
    }

    function similarProducts($itemNumber)
    {
        return $this->get($this->channel . '/' . $this->locale . '/products/' . $itemNumber . '/similar');
    }

    function recommendedProducts($itemNumber)
    {
        return $this->get($this->channel . '/' . $this->locale . '/products/' . $itemNumber . '/recommended');
    }

    function recommendations($order, $size = null)
    {
        return $this->post($this->channel . '/' . $this->locale . '/recommendations', $size ? ['size' => $size] : null, $order);
    }

    public function categories($depth = null, $products = true)
    {
        $query = ['products' => ($products ? 'true' : 'false')];
        if ($depth) {
            $query['depth'] = $depth;
        }

        return $this->get($this->channel . '/' . $this->locale . '/categories', $query);
    }

    public function category($code, $depth = null, $filterAggregates = null)
    {
        $query = [];
        if ($depth) {
            $query['depth'] = $depth;
        }
        if ($filterAggregates) {
            $query['filter-aggregates'] = $filterAggregates;
        }

        return $this->get($this->channel . '/' . $this->locale . '/categories/' . $code, count($query) > 0 ? $query : null);
    }

    public function categoryFilters($code)
    {
        return $this->get($this->channel . '/' . $this->locale . '/categories/' . $code . '/filter-aggregates');
    }
}
