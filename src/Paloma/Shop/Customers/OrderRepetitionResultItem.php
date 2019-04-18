<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\Checkout\CartItemInterface;

class OrderRepetitionResultItem implements OrderRepetitionResultItemInterface
{
    private $status;

    /**
     * @var OrderItemInterface
     */
    private $orderItem;

    /**
     * @var CartItemInterface
     */
    private $cartItem;

    public function __construct(string $status, OrderItemInterface $orderItem, CartItemInterface $cartItem = null)
    {
        $this->status = $status;
        $this->orderItem = $orderItem;
        $this->cartItem = $cartItem;
    }

    function getStatus(): string
    {
        return $this->status;
    }

    function getOrderItem(): OrderItemInterface
    {
        return $this->orderItem;
    }

    function getCartItem(): ?CartItemInterface
    {
        return $this->cartItem;
    }
}