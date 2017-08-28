<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\BaseClient;
use Paloma\Shop\PalomaProfiler;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CheckoutClient extends BaseClient implements CheckoutClientInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    const ORDERS_PATH = 'orders';

    public function __construct($baseUrl, $apiKey, SessionInterface $session = null, LoggerInterface $logger = null, PalomaProfiler $profiler = null)
    {
        parent::__construct($baseUrl, $apiKey, $logger, $profiler);

        if ($session == null) {
            $session = new Session();
        }

        $this->session = $session;
    }

    /**
     * @param $country
     * @param $language
     * @return Cart
     */
    function cart($country, $language)
    {
        return new Cart($country, $language, $this, $this->session);
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

    function getPaymentMethods($orderId)
    {
        return $this->get('payment-methods', ['order-id' => $orderId]);
    }

    function initPayment($payment)
    {
        return $this->post('payments', null, $payment);
    }

    function getShippingMethods($orderId)
    {
        return $this->get('shipping-methods', ['order-id' => $orderId]);
    }
}