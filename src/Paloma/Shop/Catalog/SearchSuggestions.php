<?php

namespace Paloma\Shop\Catalog;

class SearchSuggestions implements SearchSuggestionsInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getCategories(): array
    {
        return array_map(function($elem) {
            return new CategoryReference($elem);
        }, ($this->data['categories'] ?? []));
    }

    function getProducts(): array
    {
        return array_map(function($elem) {
            return new ProductReference($elem);
        }, ($this->data['products'] ?? []));
    }
}