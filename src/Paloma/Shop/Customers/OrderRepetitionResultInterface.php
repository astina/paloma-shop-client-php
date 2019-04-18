<?php

namespace Paloma\Shop\Customers;

interface OrderRepetitionResultInterface
{
    /**
     * No items from the given order have been added to the cart
     */
    const STATUS_FAILED = 'failed';

    /**
     * At least one item from the given order have been added to the cart
     */
    const STATUS_SUCCESS = 'success';

    /**
     * Whether the order repetition (adding all order items from a previous order to the cart)
     * has been successful.
     *
     * @return string One of: 'failed', 'success'
     */
    function getStatus(): string;

    /**
     * @return OrderRepetitionResultItemInterface[]
     */
    function getItems(): array;
}