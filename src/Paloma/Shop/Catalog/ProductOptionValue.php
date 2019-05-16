<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\Common\SelfNormalizing;

class ProductOptionValue implements ProductOptionValueInterface, SelfNormalizing
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
        return $this->data['label'];
    }

    function getVariants(): array
    {
        return $this->data['variants'];
    }

    public function _normalize(): array
    {
        return $this->data;
    }
}