<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\Common\SelfNormalizing;

class FilterValue implements FilterValueInterface, SelfNormalizing
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

    function getLabel(): string
    {
        return isset($this->data['label'])
            ? $this->data['label']
            : $this->data['value'];
    }

    function getCount(): int
    {
        return (int) $this->data['count'];
    }

    public function _normalize(): array
    {
        return [
            'value' => $this->data['value'],
            'label' => $this->getLabel(),
            'count' => $this->getCount(),
        ];
    }
}