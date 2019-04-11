<?php

namespace Paloma\Shop\Checkout;

interface OrderDraftInterface
{
    function getBilling(): OrderBillingInterface;

    function getShipping(): OrderShippingInterface;

    /**
     * @return OrderItemDraftInterface[]
     */
    function getItems(): array;

    /**
     * @return string Total price for all items as formatted string including currency symbol (e.g. "CHF 12.80")
     */
    function getItemsPrice(): string;

    /**
     * @return string Shipping price as formatted string including currency symbol (e.g. "CHF 12.80")
     */
    function getShippingPrice(): ?string;

    /**
     * @return OrderAdjustmentInterface[]
     */
    function getReductions(): array;

    /**
     * Returns a list of taxes being added additionally
     *
     * @return OrderAdjustmentInterface[]
     */
    function getTaxes(): array;

    /**
     * @return string Total price for the order as formatted string including currency symbol (e.g. "CHF 12.80")
     */
    function getTotalPrice(): string;

    /**
     * Returns a list of taxes already included in the total price
     *
     * @return OrderAdjustmentInterface[]
     */
    function getIncludedTaxes(): array;
}