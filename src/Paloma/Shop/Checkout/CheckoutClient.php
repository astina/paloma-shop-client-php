<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\BaseClient;
use Psr\Log\LoggerInterface;

class CheckoutClient extends BaseClient implements CheckoutClientInterface
{
    const ORDERS_PATH = 'orders';

    public function __construct($baseUrl, $apiKey, $debug = false, LoggerInterface $logger = null)
    {
       parent::__construct($baseUrl, $apiKey, $debug, $logger);
    }

    function createOrder($order)
    {
        return $this->post(self::ORDERS_PATH, null, $order);
    }

    function deleteOrder($id)
    {
        return $this->delete(self::ORDERS_PATH . '/' . $id);
    }

    function getOrder($id, $languageCode = null)
    {
        return $this->get(self::ORDERS_PATH . '/' . $id, $languageCode ? ['language' => $languageCode] : null);
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

    function orderItemQuantity($orderId, $languageCode = null)
    {
        if ($orderId) {
            $order = $this->getOrder($orderId, $languageCode);
            if ($order && $order['items']) {
                $count = 0;
                for ($i = 0; $i < count($order['items']); $i++) {
                    $count += $order['items'][$i]['quantity'];
                }
                return $count;
            }
        }
        return 0;
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

    function getCartId($session)
    {
        $cartId = $session->get('cartId');
        if (!$cartId) {
            $cart = createOrder(['country' => 'ch', 'language' => 'de']);
            $session->set('cartId', $cart['id']);
            $cartId = $cart['id'];
        }
        return $cartId;
    }
}