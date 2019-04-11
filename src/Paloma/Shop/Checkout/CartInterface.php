<?php

namespace Paloma\Shop\Checkout;

interface CartInterface
{
    /**
     * @return CartItemInterface[]
     */
    function getItems(): array;

    /**
     * @return bool True if the cart contains no items
     */
    function isEmpty(): bool;

    /**
     * If an operation requires modification to the order that where not explicitly requested by the client,
     * those modifications are listed here. Example: Item was removed because it is no longer available
     * @return CartModificationInterface[]
     */
    function getModifications(): array;
}