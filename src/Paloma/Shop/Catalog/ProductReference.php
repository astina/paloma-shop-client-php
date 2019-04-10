<?php

namespace Paloma\Shop\Catalog;

class ProductReference implements ProductReferenceInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getItemNumber(): string
    {
        return $this->data['itemNumber'];
    }

    function getName(): string
    {
        return $this->data['name'];
    }

    function getSlug(): string
    {
        return $this->data['slug'];
    }

    function getCategory(): CategoryReferenceInterface
    {
        return new CategoryReference($this->data['category']);
    }

    function getMainCategory(): CategoryReferenceInterface
    {
        return $this->data['mainCategory']
            ? new CategoryReference($this->data['mainCategory'])
            : null;
    }
}