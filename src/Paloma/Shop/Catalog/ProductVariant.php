<?php

namespace Paloma\Shop\Catalog;

class ProductVariant implements ProductVariantInterface
{
    private $data;

    /**
     * @var Price
     */
    private $price;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->price = new Price($data['pricing']);
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
        return $this->price->getPrice();
    }

    function getOriginalPrice(): ?string
    {
        return $this->data['pricing']['originalGrossPriceFormatted'];
    }

    function getTaxRate(): string
    {
        return $this->price->getTaxRate();
    }

    function isTaxIncluded(): bool
    {
        return $this->price->isTaxIncluded();
    }

    /**
     * @return ProductVariantOptionInterface[]
     */
    function getOptions(): array
    {
        return array_map(function($elem) {
            return new ProductVariantOption($elem);
        }, $this->data['options'] ?? []);
    }

    function getAttributes(): array
    {
        $attributes = [];
        foreach (array_values($this->data['attributes'] ?? []) as $attr) {
            if ($attr['display'] === 'product') {
                $attributes[$attr['type']] = new ProductAttribute($attr);
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
     * @return ImageInterface[]
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