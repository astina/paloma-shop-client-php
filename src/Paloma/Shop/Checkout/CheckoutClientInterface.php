<?php

namespace Paloma\Shop\Checkout;

interface CheckoutClientInterface
{
    function createOrder($order);

    function deleteOrder($id);

    function getOrder($id, $languageCode);

    function setAddresses($orderId, $addresses);

    function addCoupon($orderId, $coupon);

    function deleteCoupon($orderId, $code);

    function finalizeOrder($id);

    function addOrderItem($orderId, $item);

    function setPaymentMethod($orderId, $method);

    function purchaseOrder($id);

    function setShippingMethod($orderId, $method);

    function setUser($orderId, $user);

    function deleteOrderItem($orderId, $itemId);

    function updateOrderItem($orderId, $itemId, $item);

    function getPaymentMethods($orderId);

    function initializePayment($payment);

    function getShippingMethods($orderId);
}