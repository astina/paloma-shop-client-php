<?php

namespace Paloma\Shop\Checkout;

interface CheckoutClientInterface
{
    /**
     * @return Cart
     */
    function cart();

    function createOrder($order);

    function getOrder($id, $languageCode = null);

    function deleteOrder($id);

    function addOrderItem($orderId, $item);

    function updateOrderItem($orderId, $itemId, $item);

    function deleteOrderItem($orderId, $itemId);

    function setCustomer($orderId, $customer);

    function setAddresses($orderId, $addresses);

    function getShippingMethods($orderId);

    function getShippingMethodOptions($orderId, $methodName, $from = null, $until = null);

    function setShippingMethod($orderId, $method);

    function getPaymentMethods($orderId);

    function setPaymentMethod($orderId, $method);

    function addCoupon($orderId, $coupon);

    function deleteCoupon($orderId, $code);

    function finalizeOrder($id);

    function initPayment($orderId, $payment);

    function purchaseOrder($id);
}
