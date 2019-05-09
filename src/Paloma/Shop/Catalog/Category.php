<?php

namespace Paloma\Shop\Catalog;

class Category implements CategoryInterface
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

    function getTitle(): ?string
    {
        return $this->data['pageTitle'] ?: $this->getName();
    }

    function getDescription(): ?string
    {
        return $this->data['footerText'] ?: null;
    }

    function getMetaDescription(): ?string
    {
        return $this->data['metaDescription'] ?: $this->getDescription();
    }

    function getParentCategoryCode(): ?string
    {
        return $this->data['parent'];
    }

    /**
     * @return CategoryInterface[]
     */
    function getSubCategories(): ?array
    {
        return isset($this->data['subCategories'])
            ? array_map(function($elem) {
                return new Category($elem);
            }, $this->data['subCategories'])
            : null;
    }

    /**
     * @return FilterAggregateInterface[]
     */
    function getFilterAggregates(): ?array
    {
        return isset($this->data['filterAggregates'])
            ? array_map(function($elem) {
                return new FilterAggregate($elem);
            }, $this->data['filterAggregates'])
            : null;
    }

    /**
     * @return CategoryReferenceInterface[]
     */
    function getAncestors(): array
    {
        return array_map(function($elem) {
            return new CategoryReference($elem);
        }, $this->data['ancestors'] ?? []);
    }
}