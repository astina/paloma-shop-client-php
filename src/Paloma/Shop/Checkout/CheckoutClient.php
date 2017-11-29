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

    public function __construct($baseUrl, $apiKey, $channel, $locale, SessionInterface $session = null, LoggerInterface $logger = null, PalomaProfiler $profiler = null)
    {
        parent::__construct($baseUrl, $apiKey, $channel, $locale, $logger, $profiler);

        if ($session == null) {
            $session = new Session();
        }

        $this->session = $session;
    }

    function cart()
    {
        return new Cart($this->channel, $this->locale, $this, $this->session);
    }

    function createOrder($order)
    {
        return $this->post($this->channel . '/' . self::ORDERS_PATH, null, $order);
    }

    function getOrder($id, $locale = null)
    {
        return $this->get($this->channel . '/' . self::ORDERS_PATH . '/' . $id, $locale ? ['locale' => $locale] : null);
    }

    function deleteOrder($id)
    {
        return $this->delete($this->channel . '/' . self::ORDERS_PATH . '/' . $id);
    }

    function addOrderItem($orderId, $item)
    {
        return $this->post($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/items', null, $item);
    }

    function updateOrderItem($orderId, $itemId, $item)
    {
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/items/' . $itemId, null, $item);
    }

    function deleteOrderItem($orderId, $itemId)
    {
        return $this->delete($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/items/' . $itemId);
    }

    function setCustomer($orderId, $customer)
    {
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/customer', null, $customer);
    }

    function setAddresses($orderId, $addresses)
    {
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/addresses', null, $addresses);
    }

    function getShippingMethods($orderId)
    {
        return $this->get($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/shipping-methods');
    }

    function setShippingMethod($orderId, $method)
    {
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/shipping-method', null, $method);
    }

    function getPaymentMethods($orderId)
    {
        return $this->get($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/payment-methods');
    }

    function setPaymentMethod($orderId, $method)
    {
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/payment-method', null, $method);
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

    function initPayment($orderId, $payment)
    {
        return $this->post($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/payments', null, $payment);
    }

    function purchaseOrder($id)
    {
        return $this->post($this->channel . '/' . self::ORDERS_PATH . '/' . $id . '/purchase');
    }

}
