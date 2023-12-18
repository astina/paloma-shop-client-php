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
        return $this->data['valueCode'] ?? $this->data['value'];
    }

    function getLabel(): string
    {
        return $this->data['label'] ?? $this->data['value'];
    }

    function getCount(): int
    {
        return (int) $this->data['count'];
    }

    function getTotal(): int
    {
        return (int) $this->data['total'];
    }

    public function _normalize(): array
    {
        return [
            'value' => $this->getValue(),
            'label' => $this->getLabel(),
            'count' => $this->getCount(),
            'total' => $this->getTotal(),
        ];
    }
}