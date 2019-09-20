<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\BaseClient;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CheckoutClient extends BaseClient implements CheckoutClientInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    const ORDERS_PATH = 'orders';

    /**
     * CheckoutClient accepts an array of constructor parameters.
     *
     * - session: (opt) a SessionInterface implementation
     *
     * And all parameters of BaseClient.
     *
     * @param string $baseUrl
     * @param array $options
     */
    public function __construct($baseUrl, array $options)
    {
        parent::__construct($baseUrl, $options);

        $this->session = empty($options['session']) ? new Session() : $options['session'];
    }

    function cart($orderId = null)
    {
        return new Cart($this->channel, $this->locale, $this, $this->session, $orderId);
    }

    function getOrders($userId = null, $customerId = null, $size = null, $locale = null)
    {
        $query = [];
        if ($userId !== null) {
            $query['userId'] = $userId;
        }
        if ($customerId !== null) {
            $query['customerId'] = $customerId;
        }
        if ($size !== null) {
            $query['size'] = $size;
        }
        $query['locale'] = $locale !== null ? $locale : $this->locale;
        return $this->get($this->channel . '/' . self::ORDERS_PATH, $query);
    }

    function createOrder($order)
    {
        $order = $order ?: [];
        $order['locale'] = isset($order['locale']) ? $order['locale'] : $this->locale;

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

    function getShippingMethodOptions($orderId, $methodName, $from = null, $until = null)
    {
        $query = [];
        if ($from !== null) {
            $query['from'] = $from;
        }
        if ($until !== null) {
            $query['until'] = $until;
        }
        return $this->get($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/shipping-methods/' . $methodName, $query);
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

    function setBroker($orderId, $broker)
    {
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/broker', null, $broker);
    }

    function setComment($orderId, $comment)
    {
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/comment', null, $comment);
    }
}
