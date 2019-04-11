<?php

namespace Paloma\Shop\Checkout;

use InvalidArgumentException;
use Paloma\Shop\Catalog\ImageInterface;
use Paloma\Shop\Catalog\Price;
use Paloma\Shop\Catalog\Product;
use Paloma\Shop\Catalog\ProductInterface;
use Paloma\Shop\Catalog\ProductVariant;
use Paloma\Shop\Catalog\ProductVariantInterface;

class CartItem implements CartItemInterface
{
    private $data;

    private $variant;

    private $availableQuantity;

    private $productData;

    public function __construct(array $data, int $availableQuantity = null, array $productData = null)
    {
        if (!isset($data['variant'])) {
            throw new InvalidArgumentException('variant missing in cart item data');
        }

        $this->data = $data;
        $this->variant = new ProductVariant($data['variant']);

        $this->availableQuantity = $availableQuantity;
        $this->productData = $productData;
    }

    function getId(): string
    {
        return $this->data['id'];
    }

    function getQuantity(): int
    {
        return (int)$this->data['quantity'];
    }

    function getTitle(): string
    {
        return $this->data['title'];
    }

    function getDescription(): ?string
    {
        return $this->data['description'];
    }

    function getSku(): string
    {
        return $this->variant->getSku();
    }

    function getItemNumber(): string
    {
        return $this->data['itemNumber'];
    }

    function getImage(): ImageInterface
    {
        return $this->variant->getFirstImage();
    }

    function getUnitPrice(): string
    {
        return (new Price($this->data['unitPricing']))->getPrice();
    }

    function getOriginalPrice(): string
    {
        return $this->variant->getPrice();
    }

    function getItemPrice(): string
    {
        return (new Price($this->data['itemPricing']))->getPrice();
    }

    function getAvailableQuantity(): int
    {
        if ($this->availableQuantity) {
            return $this->availableQuantity;
        }

        $availability = $this->data['variant']['availability'];

        if (!$availability['available']) {
            return 0;
        }

        return $availability['availableStock'];
    }

    function getProduct(): ?ProductInterface
    {
        return $this->productData === null
            ? null
            : new Product($this->productData);
    }

    function getProductVariant(): ?ProductVariantInterface
    {
        if ($this->productData) {
            foreach (($this->productData['variants'] ?? []) as $variant) {
                if ($variant['sku'] === $this->data['sku']) {
                    return new ProductVariant($variant);
                }
            }
        }

        if (isset($this->data['variant'])) {
            return new ProductVariant($this->data['variant']);
        }

        return null;
    }
}