<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\Checkout\CartItemInterface;

interface OrderRepetitionResultItemInterface
{
    /**
     * Adding the order item to the cart was not successful
     */
    const STATUS_FAILED = 'failed';

    /**
     * Adding the order item to the cart was successful
     */
    const STATUS_SUCCESS = 'success';

    /**
     * @return string One of 'failed', 'success'
     */
    function getStatus(): string;

    /**
     * The order item that was attempted to be added to the cart
     *
     * @return OrderItemInterface
     */
    function getOrderItem(): OrderItemInterface;

    /**
     * Contains the added cart item in case of status=success
     *
     * @return CartItemInterface|null
     */
    function getCartItem(): ?CartItemInterface;
}