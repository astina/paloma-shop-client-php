<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\BaseClient;

class CatalogClient extends BaseClient implements CatalogClientInterface
{
    private $catalog;

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
        // Enable caching by default in the catalog endpoint
        $options['use_cache'] = isset($options['use_cache']) ? $options['use_cache'] : true;
        parent::__construct($baseUrl, $options);
        $this->catalog = isset($options['catalog']) ? $options['catalog'] : null;
    }

    public function search($search)
    {
        return $this->post($this->channel . '/' . $this->locale . '/search', $this->catalogQuery(), $search);
    }

    function searchSuggestions($query)
    {
        return $this->get($this->channel . '/' . $this->locale . '/search/suggestions', ['query' => $query] + $this->catalogQuery());
    }

    public function product($itemNumber, array $context = null)
    {
        return $this->get($this->channel . '/' . $this->locale . '/products/' . $itemNumber, $this->createContextQuery($context) + $this->catalogQuery());
    }

    function similarProducts($itemNumber, array $context = null)
    {
        return $this->get($this->channel . '/' . $this->locale . '/products/' . $itemNumber . '/similar', $this->createContextQuery($context) + $this->catalogQuery());
    }

    function recommendedProducts($itemNumber, array $context = null)
    {
        return $this->get($this->channel . '/' . $this->locale . '/products/' . $itemNumber . '/recommended', $this->createContextQuery($context) + $this->catalogQuery());
    }

    function recommendations($order, $size = null, array $context = null)
    {
        $query = ($this->createContextQuery() ?? []) + $this->catalogQuery();
        if ($size) {
            $query['size'] = $size;
        }

        return $this->post($this->channel . '/' . $this->locale . '/recommendations',
            $query, $order);
    }

    public function categories($depth = null, $products = true, $includeUnlisted = null)
    {
        $query = ['products' => ($products ? 'true' : 'false')] + $this->catalogQuery();
        if ($depth) {
            $query['depth'] = $depth;
        }
        if ($includeUnlisted) {
            $query['include-unlisted'] = $includeUnlisted;
        }

        return $this->get($this->channel . '/' . $this->locale . '/categories', $query);
    }

    public function category($code, $depth = null, $filterAggregates = null, $includeUnlisted = null)
    {
        $query = $this->catalogQuery();
        if ($depth) {
            $query['depth'] = $depth;
        }
        if ($filterAggregates) {
            $query['filter-aggregates'] = $filterAggregates;
        }
        if ($includeUnlisted) {
            $query['include-unlisted'] = $includeUnlisted;
        }

        return $this->get($this->channel . '/' . $this->locale . '/categories/' . $code,
            count($query) > 0 ? $query : null);
    }

    public function categoryFilters($code)
    {
        return $this->get($this->channel . '/' . $this->locale . '/categories/' . $code . '/filter-aggregates', $this->catalogQuery());
    }

    function listBySkus(array $skus, $omitOtherVariants = false, $includeInactiveProducts = false, array $context = null)
    {
        return $this->post($this->channel . '/' . $this->locale . '/products/by-sku',
            $this->createContextQuery($context) + $this->catalogQuery(),
            [
                'skus' => $skus,
                'omitOtherVariants' => $omitOtherVariants,
                'includeInactiveProducts' => $includeInactiveProducts,
            ]);
    }

    private function createContextQuery(array $context = null)
    {
        if (!$context) {
            return null;
        }

        $query = [];

        if (isset($context['priceGroups'])) {
            $query['context.priceGroups'] = $context['priceGroups'];
        }

        if (isset($context['date'])) {
            $query['context.date'] = $context['date'];
        }

        if (isset($context['currency'])) {
            $query['context.currency'] = $context['currency'];
        }

        return $query;
    }

    public function catalogQuery(): array
    {
        if ($this->catalog) {
            return ['catalog' => $this->catalog];
        }

        return [];
    }
}
