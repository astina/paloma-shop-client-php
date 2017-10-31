<?php

namespace Paloma\Shop\Checkout;

interface CheckoutClientInterface
{
    /**
     * @param $country
     * @param $language
     * @return Cart
     */
    function cart($country, $language);

    function createOrder($order);

    function deleteOrder($id);

    function getOrder($id, $languageCode = null);

    function setAddresses($orderId, $addresses);

    function addCoupon($orderId, $coupon);

    function deleteCoupon($orderId, $code);

    function finalizeOrder($id);

    function addOrderItem($orderId, $item);

    function setPaymentMethod($orderId, $method);

    function purchaseOrder($id);

    function setShippingMethod($orderId, $method);

    function setCustomer($orderId, $customer);

    function deleteOrderItem($orderId, $itemId);

    function updateOrderItem($orderId, $itemId, $item);

    function getPaymentMethods($orderId);

    function initPayment($orderId, $payment);

    function getShippingMethods($orderId);

    //TODO av: implement payment status?
}