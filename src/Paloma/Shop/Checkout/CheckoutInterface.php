<?php

namespace Paloma\Shop\Checkout;

use DateTime;
use Paloma\Shop\Common\AddressInterface;
use Paloma\Shop\Customers\CustomerInterface;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\CartIsEmpty;
use Paloma\Shop\Error\CartItemNotFound;
use Paloma\Shop\Error\InsufficientStock;
use Paloma\Shop\Error\InvalidCouponCode;
use Paloma\Shop\Error\InvalidInput;
use Paloma\Shop\Error\InvalidShippingTargetDate;
use Paloma\Shop\Error\NonElectronicPaymentMethod;
use Paloma\Shop\Error\OrderNotReadyForPayment;
use Paloma\Shop\Error\OrderNotReadyForPurchase;
use Paloma\Shop\Error\ProductVariantNotFound;
use Paloma\Shop\Error\ProductVariantUnavailable;
use Paloma\Shop\Error\UnknownPaymentMethod;
use Paloma\Shop\Error\UnknownShippingMethod;
use Paloma\Shop\Security\UserDetailsInterface;

interface CheckoutInterface
{
    /**
     * Returns the cart for the current user/session.
     *
     * @return CartInterface
     * @throws BackendUnavailable
     */
    function getCart(): CartInterface;

    /**
     * Adds a cart item to the current cart
     *
     * @param string $sku Product variant sku
     * @param int $quantity
     * @return CartInterface
     * @throws ProductVariantNotFound
     * @throws ProductVariantUnavailable
     * @throws ProductVariantNotFound
     * @throws InsufficientStock
     * @throws BackendUnavailable
     */
    function addCartItem(string $sku, int $quantity = 1): CartInterface;

    /**
     * Changes the quantity of the given cart item
     *
     * @param string $itemId
     * @param int $quantity
     * @return CartInterface
     * @throws CartItemNotFound
     * @throws ProductVariantUnavailable
     * @throws InsufficientStock
     * @throws BackendUnavailable
     */
    function updateCartItem(string $itemId, int $quantity): CartInterface;

    /**
     * Removes the cart item with the given ID
     *
     * @param string $itemId
     * @return CartInterface
     * @throws BackendUnavailable
     */
    function removeCartItem(string $itemId): CartInterface;

    /**
     * @return OrderDraftInterface
     * @throws CartIsEmpty
     * @throws BackendUnavailable
     */
    function getOrderDraft(): OrderDraftInterface;

    /**
     * @param CustomerInterface $customer
     * @param UserDetailsInterface|null $user
     * @return OrderDraftInterface
     * @throws BackendUnavailable
     */
    function setCustomer(CustomerInterface $customer, UserDetailsInterface $user = null): OrderDraftInterface;

    /**
     * Update the current order's shipping and billing address. If only the billing address is provided, it will be
     * used as shipping address as well.
     *
     * @param AddressInterface $billingAddress
     * @param AddressInterface|null $shippingAddress
     * @return OrderDraftInterface
     * @throws InvalidInput
     * @throws BackendUnavailable
     */
    function setAddresses(AddressInterface $billingAddress, AddressInterface $shippingAddress = null): OrderDraftInterface;

    /**
     * Returns a list of available shipping methods for the current order.
     *
     * @return ShippingMethodInterface[]
     * @throws BackendUnavailable
     */
    function getShippingMethods(): array;

    /**
     * Returns available shipping options for the given shipping method.
     *
     * @param string $shippingMethod Shipping method name
     * @param DateTime|null $from
     * @param DateTime|null $until
     * @return ShippingMethodOptionsInterface
     * @throws UnknownShippingMethod
     * @throws InvalidInput
     * @throws BackendUnavailable
     */
    function getShippingMethodOptions(string $shippingMethod, DateTime $from = null, DateTime $until = null): ShippingMethodOptionsInterface;

    /**
     * Updates the current order's shipping method
     *
     * @param string $shippingMethod Shipping method name
     * @param DateTime|null $targetDate
     * @return OrderDraftInterface
     * @throws UnknownShippingMethod
     * @throws InvalidShippingTargetDate
     * @throws BackendUnavailable
     */
    function setShippingMethod(string $shippingMethod, DateTime $targetDate = null): OrderDraftInterface;

    /**
     * Returns a list of available payment methods for the current order.
     *
     * @return PaymentMethodInterface[]
     * @throws BackendUnavailable
     */
    function getPaymentMethods(): array;

    /**
     * @param string $paymentMethod Payment method name
     * @return OrderDraftInterface
     * @throws UnknownPaymentMethod
     * @throws BackendUnavailable
     */
    function setPaymentMethod(string $paymentMethod): OrderDraftInterface;

    /**
     * @param string $couponCode
     * @return OrderDraftInterface
     * @throws InvalidCouponCode
     * @throws BackendUnavailable
     */
    function addCouponCode(string $couponCode): OrderDraftInterface;

    /**
     * @param String $couponCode
     * @return OrderDraftInterface
     * @throws BackendUnavailable
     */
    function removeCouponCode(String $couponCode): OrderDraftInterface;

    /**
     * Get information to start the payment process for the current order.
     * This is only possible if the selected payment method is of type 'electronic'
     *
     * @param PaymentInitParametersInterface $params
     * @return PaymentDraftInterface
     * @throws OrderNotReadyForPayment
     * @throws NonElectronicPaymentMethod
     * @throws BackendUnavailable
     */
    function initializePayment(PaymentInitParametersInterface $params): PaymentDraftInterface;

    /**
     * Place the current order for purchase. If successful, this will reset the current shopping cart.
     *
     * @return OrderPurchaseInterface
     * @throws OrderNotReadyForPurchase
     * @throws BackendUnavailable
     */
    function purchase(): OrderPurchaseInterface;
}