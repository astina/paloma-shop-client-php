<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\Common\Price;

class Cart implements CartInterface
{
    private $data;

    public static function createEmpty(): Cart
    {
        return new Cart([
            'items' => []
        ]);
    }

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return CartItemInterface[]
     */
    function getItems(): array
    {
        return array_map(function($elem) {
            return new CartItem($elem);
        }, $this->data['items'] ?? []);
    }

    /**
     * @return int Cart items count
     */
    function itemsCount(): int
    {
        return count($this->data['items'] ?? []);
    }

    /**
     * @return int Number of cart items times quantities
     */
    function unitsCount(): int
    {
        return array_reduce($this->getItems(), function($carry, CartItemInterface $item) {
            return $carry + $item->getQuantity();
        }, 0);
    }

    function isEmpty(): bool
    {
        return count($this->data['items'] ?? []) === 0;
    }

    function getItemsPrice(): string
    {
        return (new Price(
            $this->data['itemsPricing']['currency'],
            $this->data['itemsPricing']['grossPriceFormatted'])
        )->getPrice();
    }

    /**
     * @return CartModificationInterface[]
     */
    function getModifications(): array
    {
        return array_map(function($elem) {
            return new CartModification($elem);
        }, $this->data['modifications'] ?? []);
    }
}