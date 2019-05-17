<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\Common\SelfNormalizing;

class FilterAggregate implements FilterAggregateInterface, SelfNormalizing
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getName(): string
    {
        return $this->data['name'];
    }

    function getLabel(): string
    {
        return $this->data['label'];
    }

    function getType(): string
    {
        return $this->data['type'];
    }

    function getValues(): array
    {
        return array_map(function($elem) {
            return new FilterValue($elem);
        }, $this->data['values']);
    }

    function getMin(): ?float
    {
        return isset($this->data['min']) ? (float) $this->data['min'] : null;
    }

    function getMax(): ?float
    {
        return isset($this->data['max']) ? (float) $this->data['max'] : null;
    }

    public function _normalize(): array
    {
        return [
            'name' => $this->data['name'],
            'label' => $this->data['label'],
            'type' => $this->data['type'],
            'values' => array_map(function($elem) {
                return $elem->_normalize();
            }, $this->getValues()),
            'min' => $this->getMin(),
            'max' => $this->getMax(),
        ];
    }
}