<?php

namespace Paloma\Shop\Checkout;

interface CartModificationInterface
{
    /**
     * @return string Modification code. "item_price_changed" or "item_removed"
     */
    function getCode(): string;

    /**
     * @return string ID of the affected order item (may be null)
     */
    function getItemId(): ?string;

    /**
     * @return array Arbitrary key-value pairs with information about the modification
     */
    function getParams(): array;
}