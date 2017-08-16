<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\BaseClient;

class CheckoutClient extends BaseClient implements CheckoutClientInterface
{
    const ORDERS_PATH = 'orders';

    public function __construct($baseUrl, $apiKey, $debug = false)
    {
       parent::__construct($baseUrl, $apiKey, $debug);
    }

    function createOrder($order)
    {
        return $this->post(self::ORDERS_PATH, null, $order);
    }

    function deleteOrder($id)
    {
        return $this->delete(self::ORDERS_PATH . '/' . $id);
    }

    function getOrder($id, $languageCode)
    {
        return $this->get(self::ORDERS_PATH . '/' . $id, ['language' => $languageCode]);
    }

    function setAddresses($orderId, $addresses)
    {
        return $this->put(self::ORDERS_PATH . '/' . $orderId . '/addresses', null, $addresses);
    }

    function addCoupon($orderId, $coupon)
    {
        return $this->post(self::ORDERS_PATH . '/' . $orderId . '/coupons', null, $coupon);
    }

    function deleteCoupon($orderId, $code)
    {
        return $this->delete(self::ORDERS_PATH . '/' . $orderId . '/coupons/' . $code);
    }

    function finalizeOrder($id)
    {
        return $this->post(self::ORDERS_PATH . '/' . $id . '/finalize');
    }

    function addOrderItem($orderId, $item)
    {
        return $this->post(self::ORDERS_PATH . '/' . $orderId . '/items', null, $item);
    }

    function setPaymentMethod($orderId, $method)
    {
        return $this->put(self::ORDERS_PATH . '/' . $orderId . '/payment-method', null, $method);
    }

    function purchaseOrder($id)
    {
        return $this->post(self::ORDERS_PATH . '/' . $id . '/purchase');
    }

    function setShippingMethod($orderId, $method)
    {
        return $this->put(self::ORDERS_PATH . '/' . $orderId . '/shipping-method', null, $method);
    }

    function setUser($orderId, $user)
    {
        return $this->put(self::ORDERS_PATH . '/' . $orderId . '/user', null, $user);
    }

    function deleteOrderItem($orderId, $itemId)
    {
        return $this->delete(self::ORDERS_PATH . '/' . $orderId . '/items/' . $itemId);
    }

    function updateOrderItem($orderId, $itemId, $item)
    {
        return $this->put(self::ORDERS_PATH . '/' . $orderId . '/items/' . $itemId, null, $item);
    }

    function getPaymentMethods($orderId)
    {
        return $this->get('payment-methods', ['order-id' => $orderId]);
    }

    function initializePayment($payment)
    {
        return $this->post('payments', null, $payment);
    }

    function getShippingMethods($orderId)
    {
        return $this->get('shipping-methods', ['order-id' => $orderId]);
    }
}