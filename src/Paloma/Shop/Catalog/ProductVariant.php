<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\Common\SelfNormalizing;
use Paloma\Shop\Utils\PriceUtils;

class ProductVariant implements ProductVariantInterface, SelfNormalizing
{
    private $data;

    /**
     * @var Price
     */
    private $price;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->price = new Price($data['price'] ?? $data['pricing']);
    }

    function getSku(): string
    {
        return $this->data['sku'];
    }

    function getName(): string
    {
        // Prefer name attribute over variant name because by default
        // the variant name contains the product item number
        if (isset($this->data['attributes']['name'])) {
            return (new ProductAttribute($this->data['attributes']['name']))->getValue();
        }

        return $this->data['name'];
    }

    function getPrice(): string
    {
        return $this->price->getPrice();
    }

    function getOriginalPrice(): ?string
    {
        return isset($this->data['price'])
            ? $this->data['price']['originalUnitPrice']
            : $this->data['pricing']['originalGrossPriceFormatted'];
    }

    function getReductionPercent(): ?string
    {
        if ($this->getOriginalPrice() === null) {
            return null;
        }

        if (!isset($this->data['price'])) {
            return null;
        }

        return PriceUtils::calculateReduction(
            $this->data['price']['unitPrice'],
            $this->data['price']['originalUnitPrice']);
    }

    function getReductionPercent(): ?string
    {
        if ($this->getOriginalPrice() === null) {
            return null;
        }

        if (!isset($this->data['price'])) {
            return null;
        }

        return PriceUtils::calculateReduction(
            $this->data['price']['unitPrice'],
            $this->data['price']['originalUnitPrice']);
    }

    function getTaxRate(): string
    {
        return $this->price->getTaxRate();
    }

    function isTaxIncluded(): bool
    {
        return $this->price->isTaxIncluded();
    }

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

    function getAvailability(): ProductAvailabilityInterface
    {
        return new ProductAvailability($this->data['availability']);
    }

    public function _normalize(): array
    {
        $data = [
            'sku' => $this->data['sku'],
            'name' => $this->data['name'],
            'price' => $this->getPrice(),
            'originalPrice' => $this->getOriginalPrice(),
            'taxRate' => $this->getTaxRate(),
            'taxIncluded' => $this->isTaxIncluded(),
            'attributes' => array_map(function($elem) {
                return $elem->_normalize();
            }, $this->getAttributes()),
            'images' => array_map(function($elem) {
                return $elem->_normalize();
            }, $this->getImages()),
            'availability' => $this->getAvailability()->_normalize(),
            'options' => array_map(function($elem) {
                return $elem->_normalize();
            }, $this->getOptions()),
        ];

        $data['firstImage'] = count($data['images']) === 0
            ? null
            : $data['images'][0];

        return $data;
    }
}