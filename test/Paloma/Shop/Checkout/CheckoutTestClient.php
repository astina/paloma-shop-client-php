<?php

namespace Paloma\Shop\Checkout;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CheckoutTestClient implements CheckoutClientInterface
{
    private $order;

    private $session;

    public function __construct(array $order = null)
    {
        $this->session = new Session(new MockArraySessionStorage());

        $this->order = $order;

        if ($order) {
            $this->session->start();
            $this->session->set('paloma-cart-id', ['test' => 'cart1']);
        }
    }

    /**
     * @return Cart
     */
    function cart()
    {
        return new Cart('test', 'de_CH', $this, $this->session);
    }

    function createOrder($order)
    {
        return $this->order;
    }

    function getOrder($id, $languageCode = null)
    {
        return $this->order;
    }

    function deleteOrder($id)
    {
        // TODO: Implement deleteOrder() method.
    }

    function addOrderItem($orderId, $item)
    {
        // TODO: Implement addOrderItem() method.
    }

    function updateOrderItem($orderId, $itemId, $item)
    {
        // TODO: Implement updateOrderItem() method.
    }

    function deleteOrderItem($orderId, $itemId)
    {
        // TODO: Implement deleteOrderItem() method.
    }

    function setCustomer($orderId, $customer)
    {
        // TODO: Implement setCustomer() method.
    }

    function setAddresses($orderId, $addresses)
    {
        // TODO: Implement setAddresses() method.
    }

    function getShippingMethods($orderId)
    {
        // TODO: Implement getShippingMethods() method.
    }

    function getShippingMethodOptions($orderId, $methodName, $from = null, $until = null)
    {
        // TODO: Implement getShippingMethodOptions() method.
    }

    function setShippingMethod($orderId, $method)
    {
        // TODO: Implement setShippingMethod() method.
    }

    function getPaymentMethods($orderId)
    {
        // TODO: Implement getPaymentMethods() method.
    }

    function setPaymentMethod($orderId, $method)
    {
        // TODO: Implement setPaymentMethod() method.
    }

    function addCoupon($orderId, $coupon)
    {
        // TODO: Implement addCoupon() method.
    }

    function deleteCoupon($orderId, $code)
    {
        // TODO: Implement deleteCoupon() method.
    }

    function finalizeOrder($id)
    {
        // TODO: Implement finalizeOrder() method.
    }

    function initPayment($orderId, $payment)
    {
        // TODO: Implement initPayment() method.
    }

    function purchaseOrder($id)
    {
        // TODO: Implement purchaseOrder() method.
    }

    function setBroker($orderId, $broker)
    {
        // TODO: Implement setBroker() method.
    }
}