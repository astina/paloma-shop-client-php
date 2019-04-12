<?php

namespace Paloma\Shop\Customers;

use DateTime;
use Paloma\Shop\Checkout\OrderDraftInterface;

interface OrderInterface extends OrderDraftInterface
{
    /**
     * @return string Order number
     */
    function getOrderNumber(): string;

    /**
     * Status of the order:
     * - purchased: Newly purchased, not yet processed
     * - processed: Order has been processed (confirmation mail should have been sent by now)
     * - assembly: Order contents are being assembled/readied
     * - shipped: Order has been shipped (is on its way to the shipping address)
     * - delivered: Order has been delivered
     * - canceled: Order has been canceled
     * - returned: Order has been returned in full
     * - returned_partial: Some items of the order have been returned
     * - refunded: Order payments have been refunded
     *
     * @return string Order status
     */
    function getStatus(): string;

    /**
     * @return DateTime Order purchase date
     */
    function getOrderDate(): DateTime;

    /**
     * @return OrderItemInterface[]
     */
    function getItems(): array;
}