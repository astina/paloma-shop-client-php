<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\Catalog\Image;
use Paloma\Shop\Catalog\ImageInterface;
use Paloma\Shop\Common\Price;

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
        return (new Price($this->currency, $this->data['unitPrice']))->getPrice();
    }

    function getOriginalPrice(): ?string
    {
        $unitPrice = $this->getUnitPrice();
        $originalPrice = (new Price($this->currency, $this->data['originalPrice']))->getPrice();

        return $unitPrice === $originalPrice ? null : $originalPrice;
    }

    function getItemPrice(): string
    {
        return (new Price($this->currency, $this->data['grossItemTotal']))->getPrice();
    }
}