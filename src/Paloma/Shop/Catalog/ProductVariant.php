<?php

namespace Paloma\Shop\Catalog;

class ProductVariant implements ProductVariantInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getSku(): string
    {
        return $this->data['sku'];
    }

    function getName(): string
    {
        return $this->data['name'];
    }

    function getPrice(): string
    {
        return sprintf('%s %s',
            $this->data['pricing']['currency'],
            $this->isTaxIncluded()
                ? $this->data['pricing']['grossPriceFormatted']
                : $this->data['pricing']['netPriceFormatted']);
    }

    function getOriginalPrice(): string
    {
        return $this->data['pricing']['originalGrossPriceFormatted'];
    }

    function getTaxRate(): string
    {
        return $this->data['pricing']['taxes']['vat']['rateFormatted'];
    }

    function isTaxIncluded(): bool
    {
        return true;
    }

    /**
     * @return ProductVariantOption[]
     */
    function getOptions(): array
    {
        return array_map(function($elem) {
            return new ProductVariantOption($elem);
        }, $this->data['options'] ?? []);
    }

    /**
     * @return ProductAttribute[]
     */
    function getAttributes(): array
    {
        $attributes = [];
        foreach (array_values($this->data['attributes'] ?? []) as $attr) {
            if ($attr['display'] === 'product') {
                array_push($attributes, new ProductAttribute($attr));
            }
        }

        return $attributes;
    }

    function getAttribute(string $type): ?ProductAttributeInterface
    {
        if (isset($this->data['attributes'][$type])
            && $this->data['attributes'][$type]['display'] === 'product') {
            return new ProductAttribute($this->data['attributes'][$type]);
        }

        return null;
    }

    /**
     * @return Image[]
     */
    function getImages(): array
    {
        return array_map(function($elem) {
            return new Image($elem);
        }, $this->data['images'] ?? []);
    }

    function getFirstImage(): ?ImageInterface
    {
        return count($this->data['images'] ?? []) === 0
            ? null
            : new Image($this->data['images'][0]);
    }
}