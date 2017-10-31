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

    public function __construct($baseUrl, $apiKey, $channel, SessionInterface $session = null, LoggerInterface $logger = null, PalomaProfiler $profiler = null)
    {
        parent::__construct($baseUrl, $apiKey, $channel, $logger, $profiler);

        if ($session == null) {
            $session = new Session();
        }

        $this->session = $session;
    }

    /**
     * @param $locale
     * @param $language
     * @return Cart
     */
    function cart($locale, $language)
    {
        return new Cart($locale, $language, $this, $this->session);
    }

    function createOrder($order)
    {
        return $this->post($this->channel . '/' . self::ORDERS_PATH, null, $order);
    }

    function deleteOrder($id)
    {
        return $this->delete($this->channel . '/' . self::ORDERS_PATH . '/' . $id);
    }

    function getOrder($id, $locale = null)
    {
        return $this->get($this->channel . '/' . self::ORDERS_PATH . '/' . $id, $locale ? ['locale' => $locale] : null);
    }

    function setAddresses($orderId, $addresses)
    {
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/addresses', null, $addresses);
    }

    function addCoupon($orderId, $coupon)
    {
        return $this->post($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/coupons', null, $coupon);
    }

    function deleteCoupon($orderId, $code)
    {
        return $this->delete($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/coupons/' . $code);
    }

    function finalizeOrder($id)
    {
        return $this->post($this->channel . '/' . self::ORDERS_PATH . '/' . $id . '/finalize');
    }

    function addOrderItem($orderId, $item)
    {
        return $this->post($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/items', null, $item);
    }

    function setPaymentMethod($orderId, $method)
    {
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/payment-method', null, $method);
    }

    function purchaseOrder($id)
    {
        return $this->post($this->channel . '/' . self::ORDERS_PATH . '/' . $id . '/purchase');
    }

    function setShippingMethod($orderId, $method)
    {
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/shipping-method', null, $method);
    }

    function setCustomer($orderId, $customer)
    {
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/customer', null, $customer);
    }

    function deleteOrderItem($orderId, $itemId)
    {
        return $this->delete($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/items/' . $itemId);
    }

    function updateOrderItem($orderId, $itemId, $item)
    {
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/items/' . $itemId, null, $item);
    }

    function getPaymentMethods($orderId)
    {
        return $this->get($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/payment-methods');
    }

    function initPayment($orderId, $payment)
    {
        return $this->post($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/payments', null, $payment);
    }

    function getShippingMethods($orderId)
    {
        return $this->get($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/shipping-methods');
    }
}