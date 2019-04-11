<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\Common\Page;

class ProductPage extends Page implements ProductPageInterface
{
    public static function createEmpty()
    {
        return new ProductPage([
            'content' => [],
            'size' => 0,
            'number' => 0,
            'totalElements' => 0,
            'totalPages' => 0,
            'last' => true,
            'first' => true,
            'sort' => null,
            'order' => 'asc',
        ]);
    }

    function getContent(): array
    {
        return array_map(function ($elem) {
            return new Product($elem);
        }, $this->data['content']);
    }

    function getFilterAggregates(): array
    {
        return isset($this->data['filterAggregates'])
            ? array_map(function ($elem) {
                return new FilterAggregate($elem);
            }, $this->data['filterAggregates'])
            : null;
    }
}