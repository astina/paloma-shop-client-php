<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\Common\SelfNormalizing;

class ProductOption implements ProductOptionInterface, SelfNormalizing
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getOption(): string
    {
        return $this->data['type'];
    }

    function getLabel(): string
    {
        return $this->data['label'];
    }

    function getValues(): array
    {
        return array_map(function($elem) {
            return new ProductOptionValue($elem);
        }, array_values($this->data['values']) ?? []);
    }

    public function _normalize(): array
    {
        return [
            'option' => $this->getOption(),
            'label' => $this->getLabel(),
            'values' => array_map(function($elem) {
                return $elem->_normalize();
            }, $this->getValues())
        ];
    }
}