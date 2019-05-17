<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\Common\Page;
use Paloma\Shop\Common\SelfNormalizing;

class ProductPage extends Page implements ProductPageInterface, SelfNormalizing
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

        $aggregatesData = [];
        foreach ($this->data[$field] as $aggregateData) {
            if (count($aggregateData['values']) > 0) {
                $aggregatesData[] = $aggregateData;
            }
        }

        return array_map(function($elem) {
                return new FilterAggregate($elem);
            }, $aggregatesData);
    }

    public function _normalize(): array
    {
        $data = parent::_normalize();

        $data['content'] = array_map(function($elem) {
            return $elem->_normalize();
        }, $this->getContent());

        $aggregates = $this->getFilterAggregates();
        if ($aggregates === null) {
            $data['filterAggregates'] = null;
        } else {
            $data['filterAggregates'] = array_map(function($elem) {
                return $elem->_normalize();
            }, $this->getFilterAggregates());
        }

        return $data;
    }
}