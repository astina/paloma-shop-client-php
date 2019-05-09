<?php

namespace Paloma\Shop\Catalog;

use InvalidArgumentException;

class Product implements ProductInterface
{
    private $data;

    private $_variants = null; // cache

    public function __construct(array $data)
    {
        if (count($data['variants']) === 0) {
            throw new InvalidArgumentException('variants missing in product data');
        }

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

    function getDescription(): ?string
    {
        return $this->data['description'];
    }

    function getShortDescription(): ?string
    {
        return $this->data['shortDescription'];
    }

    function getBasePrice(): string
    {
        return $this->getVariants()[0]->getPrice();
    }

    function getOriginalBasePrice(): ?string
    {
        return $this->getVariants()[0]->getOriginalPrice();
    }

    function getTaxRate(): string
    {
        return $this->getVariants()[0]->getTaxRate();
    }

    function isTaxIncluded(): bool
    {
        return $this->getVariants()[0]->isTaxIncluded();
    }

    /**
     * @return ProductVariantInterface[]
     */
    function getVariants(): array
    {
        if ($this->_variants === null) {
            $this->_variants = array_map(function($elem) {
                return new ProductVariant($elem);
            }, $this->data['variants']);
        }

        return $this->_variants;
    }

    /**
     * @return array
     */
    function getAttributes(): array
    {
        $attributes = [];
        foreach (array_values($this->data['master']['attributes'] ?? []) as $attr) {
            if ($attr['display'] === 'product') {
                $attributes[$attr['type']] = new ProductAttribute($attr);
            }
        }

        return $attributes;
    }

    function getAttribute(string $type): ?ProductAttributeInterface
    {
        if (isset($this->data['master']['attributes'][$type])
            && $this->data['master']['attributes'][$type]['display'] === 'product') {
            return new ProductAttribute($this->data['master']['attributes'][$type]);
        }

        return null;
    }

    /**
     * @return ImageInterface[]
     */
    function getImages(): array
    {
        return array_map(function($elem) {
            return new Image($elem);
        }, $this->data['master']['images'] ?? []);
    }

    function getFirstImage(): ?ImageInterface
    {
        return count($this->data['master']['images'] ?? []) === 0
            ? null
            : new Image($this->data['master']['images'][0]);
    }

    /**
     * @return CategoryReferenceInterface[]
     */
    function getCategories(): array
    {
        return array_map(function($elem) {
           return new CategoryReference($elem);
        }, $this->data['categories'] ?? []);
    }
}