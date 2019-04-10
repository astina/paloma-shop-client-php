<?php

namespace Paloma\Shop\Catalog;

class FilterAggregate implements FilterAggregateInterface
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

    function getMin(): float
    {
        return (float) $this->data['min'];
    }

    function getMax(): float
    {
        return (float) $this->data['max'];
    }
}