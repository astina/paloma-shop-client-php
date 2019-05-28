<?php

namespace Paloma\Shop\Checkout;

interface OrderDraftInterface
{
    function getCustomer(): ?OrderCustomerInterface;

    function getBilling(): OrderBillingInterface;

    function getShipping(): OrderShippingInterface;

    /**
     * @return OrderItemDraftInterface[]
     */
    function getItems(): array;

    /**
     * If an operation requires modification to the order that where not explicitly requested by the client,
     * those modifications are listed here. Example: Item was removed because it is no longer available
     * @return OrderDraftModificationInterface[]
     */
    function getModifications(): array;

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
     * Returns a list of surcharges (e.g. taxes) being added additionally
     *
     * @return OrderAdjustmentInterface[]
     */
    function getSurcharges(): array;

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

    /**
     * List of active promotions on this order
     *
     * @return OrderCouponInterface[]
     */
    function getCoupons(): array;
}