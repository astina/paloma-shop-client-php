<?php

namespace Paloma\Shop\Catalog;

use Exception;

class CatalogTestClient implements CatalogClientInterface
{
    private $products;

    private $categories;

    private $exception;

    public function __construct(array $products = [], array $categories = [], Exception $exception = null)
    {
        $this->products = $products;
        $this->categories = $categories;
        $this->exception = $exception;
    }

    function search($search)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return [
            'content' => $this->products,
            'filterAggregates' => isset($search['filterAggregates']) && $search['filterAggregates'] ? [] : null,
            'size' => isset($search['size']) ? $search['size'] : 20,
            'number' => isset($search['page']) ? $search['page'] : 0,
            'totalElements' => 1,
            'totalPages' => 1,
            'last' => true,
            'first' => true,
            'sort' => isset($search['sort']) ? $search['sort'] : null,
            'order' => isset($search['order']) ? $search['order'] : 'asc',
        ];
    }

    function searchSuggestions($query)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return [
            'categories' => $this->categories,
            'products' => $this->products,
        ];
    }

    function product($itemNumber)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return count($this->products) === 0 ? null : $this->products[0];
    }

    function similarProducts($itemNumber)
    {
        return $this->search([ 'query' => $itemNumber ]);
    }

    function recommendedProducts($itemNumber)
    {
        return $this->search([ 'query' => $itemNumber ]);
    }

    function purchasedTogether($itemNumber)
    {
        return $this->search([ 'query' => $itemNumber ]);
    }

    function recommendations($order, $size = null)
    {
        return $this->search([]);
    }

    function categories($depth = null, $products = true)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return $this->categories;
    }

    function category($code, $depth = null, $filterAggregates = null)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return count($this->categories) === 0 ? null : $this->categories[0];
    }

    function categoryFilters($code)
    {
        if ($this->exception) {
            throw $this->exception;
        }
        // TODO: Implement categoryFilters() method.
    }
}