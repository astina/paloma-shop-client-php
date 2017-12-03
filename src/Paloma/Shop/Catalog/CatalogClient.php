<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\BaseClient;

class CatalogClient extends BaseClient implements CatalogClientInterface
{
    /**
     * CatalogClient accepts an array of constructor parameters.
     *
     * All parameters of BaseClient.
     *
     * @param string $baseUrl
     * @param array $options
     */
    public function __construct($baseUrl, array $options)
    {
        parent::__construct($baseUrl, $options);
    }

    public function search($search, $useCache = true, $defaultCacheTtl = null)
    {
        return $this->post($this->channel . '/' . $this->locale . '/search', null, $search,
            $useCache, $defaultCacheTtl);
    }

    function searchSuggestions($query, $useCache = true, $defaultCacheTtl = null)
    {
        return $this->get($this->channel . '/' . $this->locale . '/search/suggestions', ['query' => $query],
            $useCache, $defaultCacheTtl);
    }

    public function product($itemNumber, $useCache = true, $defaultCacheTtl = null)
    {
        return $this->get($this->channel . '/' . $this->locale . '/products/' . $itemNumber,
            $useCache, $defaultCacheTtl);
    }

    function similarProducts($itemNumber, $useCache = true, $defaultCacheTtl = null)
    {
        return $this->get($this->channel . '/' . $this->locale . '/products/' . $itemNumber . '/similar',
            $useCache, $defaultCacheTtl);
    }

    function recommendedProducts($itemNumber, $useCache = true, $defaultCacheTtl = null)
    {
        return $this->get($this->channel . '/' . $this->locale . '/products/' . $itemNumber . '/recommended',
            $useCache, $defaultCacheTtl);
    }

    function recommendations($order, $size = null, $useCache = true, $defaultCacheTtl = null)
    {
        return $this->post($this->channel . '/' . $this->locale . '/recommendations',
            $size ? ['size' => $size] : null, $order, $useCache, $defaultCacheTtl);
    }

    public function categories($depth = null, $products = true, $useCache = true,
        $defaultCacheTtl = null)
    {
        $query = ['products' => ($products ? 'true' : 'false')];
        if ($depth) {
            $query['depth'] = $depth;
        }

        return $this->get($this->channel . '/' . $this->locale . '/categories', $query,
            $useCache, $defaultCacheTtl);
    }

    public function category($code, $depth = null, $filterAggregates = null,
        $useCache = true, $defaultCacheTtl = null)
    {
        $query = [];
        if ($depth) {
            $query['depth'] = $depth;
        }
        if ($filterAggregates) {
            $query['filter-aggregates'] = $filterAggregates;
        }

        return $this->get($this->channel . '/' . $this->locale . '/categories/' . $code,
            count($query) > 0 ? $query : null, $useCache, $defaultCacheTtl);
    }

    public function categoryFilters($code, $useCache = true, $defaultCacheTtl = null)
    {
        return $this->get($this->channel . '/' . $this->locale . '/categories/' . $code . '/filter-aggregates',
            $useCache, $defaultCacheTtl);
    }
}
