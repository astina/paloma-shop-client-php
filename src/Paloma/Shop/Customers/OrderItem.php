<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\Catalog\Image;
use Paloma\Shop\Catalog\ImageInterface;
use Paloma\Shop\Common\Price;
use Paloma\Shop\Utils\PriceUtils;

class OrderItem implements OrderItemInterface
{
    private $data;

    private $currency;

    public function __construct(array $data, ?string $currency = null)
    {
        $this->data = $data;
        $this->currency = $currency;
    }

    function getSku(): string
    {
        return $this->data['sku'];
    }

    function getCode(): string
    {
        return $this->data['code'];
    }

    function getQuantity(): int
    {
        return $this->data['quantity'];
    }

    function getTitle(): string
    {
        return $this->data['name'];
    }

    function getImage(): ?ImageInterface
    {
        return isset($this->data['image']) ? new Image($this->data['image']) : null;
    }

    function getUnitPrice(): string
    {
        return PriceUtils::format($this->currency, $this->data['unitPrice']);
    }

    function getUnit(): ?string
    {
        return $this->data['unit'] === 'piece'
            ? null
            : $this->data['unit'];
    }

    function getOriginalPrice(): ?string
    {
        if (!isset($this->data['originalPrice']) || strpos($this->data['originalPrice'], '0') === 0) {
            return null;
        }

        $unitPrice = $this->getUnitPrice();
        $originalPrice = (new Price($this->currency, $this->data['originalPrice']))->getPrice();

        return $unitPrice === $originalPrice ? null : $originalPrice;
    }

    function getItemPrice(): string
    {
        return PriceUtils::format($this->currency, $this->data['linePrice'] ?? $this->data['grossItemTotal']);
    }
}