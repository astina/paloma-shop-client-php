<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\Common\SelfNormalizing;

class ProductAttribute implements ProductAttributeInterface, SelfNormalizing
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getType(): string
    {
        return $this->data['type'];
    }

    function getLabel(): string
    {
        return $this->data['label'];
    }

    function getValue(): string
    {
        return $this->data['value'];
    }

    public function _normalize(): array
    {
        return $this->data;
    }
}