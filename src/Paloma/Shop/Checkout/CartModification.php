<?php

namespace Paloma\Shop\Checkout;

class CartModification implements CartModificationInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string Modification code. "item_price_changed" or "item_removed"
     */
    function getCode(): string
    {
        // TODO: Implement getCode() method.
    }

    /**
     * @return string ID of the affected order item (may be null)
     */
    function getItemId(): string
    {
        // TODO: Implement getItemId() method.
    }

    /**
     * @return array Arbitrary key-value pairs with information about the modification
     */
    function getParams(): array
    {
        // TODO: Implement getParams() method.
    }
}