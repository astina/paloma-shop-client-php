<?php

namespace Paloma\Shop\Catalog;

class ProductVariantOption implements ProductVariantOptionInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getOption(): string
    {
        return $this->data['option'];
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