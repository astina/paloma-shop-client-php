<?php

namespace Paloma\Shop\Checkout;

interface CheckoutClientInterface
{
    /**
     * Create a new cart with a new order or use the one already known in the
     * session. If an order ID is supplied then instead of creating a new cart
     * with a new order the order specified will be used.
     * @return Cart
     */
    function cart($orderId = null);
    
    function getOrders($userId = null, $customerId = null, $size = null, $locale = null);

    function createOrder($order);

    function getOrder($id, $locale = null);

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

    function setBroker($orderId, $broker);
}
