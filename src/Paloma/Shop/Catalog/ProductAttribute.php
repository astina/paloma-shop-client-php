<?php

namespace Paloma\Shop\Catalog;

class ProductAttribute implements ProductAttributeInterface
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
}