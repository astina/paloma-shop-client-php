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

    function getCategory(): ?CategoryReferenceInterface
    {
        return isset($this->data['category']) && isset($this->data['category']['code'])
            ? new CategoryReference($this->data['category'])
            : null;
    }

    function getMainCategory(): CategoryReferenceInterface
    {
        return isset($this->data['mainCategory']) && isset($this->data['mainCategory']['code'])
            ? new CategoryReference($this->data['mainCategory'])
            : null;
    }
}