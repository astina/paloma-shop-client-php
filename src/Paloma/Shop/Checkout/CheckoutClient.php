<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\BaseClient;
use Paloma\Shop\Customers\CustomerInterface;
use Paloma\Shop\Security\UserDetailsInterface;
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

    function checkoutOrder(CustomerInterface $customer = null, UserDetailsInterface $user = null)
    {
        return new CheckoutOrder($this->channel, $this->locale, $this, $this->session, $customer, $user);
    }

    function createOrder($order)
    {
        $order = $order ?: [];
        $order['locale'] = isset($order['locale']) ? $order['locale'] : $this->locale;

        return $this->post($this->channel . '/' . self::ORDERS_PATH, ['_meta' => 'validation'], $order);
    }

    function getOrder($id, $locale = null)
    {
        return $this->get($this->channel . '/' . self::ORDERS_PATH . '/' . $id, ['locale' => $locale, '_meta' => 'validation']);
    }

    function deleteOrder($id)
    {
        return $this->delete($this->channel . '/' . self::ORDERS_PATH . '/' . $id);
    }

    function addOrderItem($orderId, $item)
    {
        return $this->post($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/items', ['_meta' => 'validation'], $item);
    }

    function updateOrderItem($orderId, $itemId, $item)
    {
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/items/' . $itemId, ['_meta' => 'validation'], $item);
    }

    function deleteOrderItem($orderId, $itemId)
    {
        return $this->delete($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/items/' . $itemId);
    }

    function setCustomer($orderId, $customer)
    {
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/customer', ['_meta' => 'validation'], $customer);
    }

    function setAddresses($orderId, $addresses)
    {
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/addresses', ['_meta' => 'validation'], $addresses);
    }

    function getShippingMethods($orderId)
    {
        return $this->get($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/shipping-methods', ['_meta' => 'validation']);
    }

    function getShippingMethodOptions($orderId, $methodName, $from = null, $until = null)
    {
        $query = ['_meta' => 'validation'];
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
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/shipping-method', ['_meta' => 'validation'], $method);
    }

    function getPaymentMethods($orderId)
    {
        return $this->get($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/payment-methods', ['_meta' => 'validation']);
    }

    function setPaymentMethod($orderId, $method)
    {
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/payment-method', ['_meta' => 'validation'], $method);
    }

    function addCoupon($orderId, $coupon)
    {
        return $this->post($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/coupons', ['_meta' => 'validation'], $coupon);
    }

    function deleteCoupon($orderId, $code)
    {
        return $this->delete($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/coupons/' . $code);
    }

    function finalizeOrder($id)
    {
        return $this->post($this->channel . '/' . self::ORDERS_PATH . '/' . $id . '/finalize', ['_meta' => 'validation']);
    }

    function initPayment($orderId, $payment)
    {
        return $this->post($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/payments', ['_meta' => 'validation'], $payment);
    }

    function purchaseOrder($id)
    {
        return $this->post($this->channel . '/' . self::ORDERS_PATH . '/' . $id . '/purchase', ['_meta' => 'validation']);
    }

    function setBroker($orderId, $broker)
    {
        return $this->put($this->channel . '/' . self::ORDERS_PATH . '/' . $orderId . '/broker', ['_meta' => 'validation'], $broker);
    }
}
