<?php

namespace Paloma\Shop\Catalog;

class FilterValue implements FilterValueInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getValue(): string
    {
        return $this->data['value'];
    }

    function getCount(): int
    {
        return (int) $this->data['count'];
    }
}