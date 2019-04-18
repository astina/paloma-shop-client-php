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
            'sort' => [],
            'order' => [ [ 'direction' => 'DESC' ]],
        ]);
    }

    function getContent(): array
    {
        return array_map(function ($elem) {
            return new Product($elem);
        }, $this->data['content']);
    }

    function getFilterAggregates(): ?array
    {
        $field = isset($this->data['filterAggregates'])
            ? 'filterAggregates'
            : (isset($this->data['aggregates'])
                ? 'aggregates'
                : null);

        if (!$field) {
            return null;
        }

        return array_map(function($elem) {
                return new FilterAggregate($elem);
            }, $this->data[$field]);
    }
}