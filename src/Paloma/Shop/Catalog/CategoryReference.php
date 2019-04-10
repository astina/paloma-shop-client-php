<?php

namespace Paloma\Shop\Catalog;

class CategoryReference implements CategoryReferenceInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getCode(): string
    {
        return $this->data['code'];
    }

    function getName(): string
    {
        return $this->data['name'];
    }

    function getSlug(): string
    {
        return $this->data['slug'];
    }
}