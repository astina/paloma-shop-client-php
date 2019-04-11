<?php

namespace Paloma\Shop\Checkout;

use Exception;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CheckoutTestClient implements CheckoutClientInterface
{
    private $order;

    private $session;

    private $exception;

    public function __construct(array $order = null, Exception $exception = null)
    {
        $this->session = new Session(new MockArraySessionStorage());

        $this->order = $order;

        if ($order) {
            $this->session->start();
            $this->session->set('paloma-cart-id', ['test' => 'cart1']);
        }

        $this->exception = $exception;
    }

    /**
     * @return CheckoutOrder
     */
    function checkoutOrder()
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return new CheckoutOrder('test', 'de_CH', $this, $this->session);
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
    }

    function addOrderItem($orderId, $item)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return $this->order;
    }

    function updateOrderItem($orderId, $itemId, $item)
    {
        return $this->order;
    }

    function deleteOrderItem($orderId, $itemId)
    {
        return $this->order;
    }

    function setCustomer($orderId, $customer)
    {
        return $this->order;
    }

    function setAddresses($orderId, $addresses)
    {
        return $this->order;
    }

    function getShippingMethods($orderId)
    {
        return [
            [ 'name' => 'method1' ],
            [ 'name' => 'method2' ],
        ];
    }

    function getShippingMethodOptions($orderId, $methodName, $from = null, $until = null)
    {
        return [
            'validUntil' => '2019-04-11T08:06:41.875+0000',
            'delivery' => [
                [ 'targetDate' => '2019-04-12' ],
                [ 'targetDate' => '2019-04-13' ],
                [ 'targetDate' => '2019-04-14' ],
            ]
        ];
    }

    function setShippingMethod($orderId, $method)
    {
        return $this->order;
    }

    function getPaymentMethods($orderId)
    {
        return [
            [ 'name' => 'method1', 'type' => 'invoice' ],
            [ 'name' => 'method2', 'type' => 'electronic', 'provider' => 'datatrans' ],
        ];
    }

    function setPaymentMethod($orderId, $method)
    {
        return $this->order;
    }

    function addCoupon($orderId, $coupon)
    {
        return $this->order;
    }

    function deleteCoupon($orderId, $code)
    {
        return $this->order;
    }

    function finalizeOrder($id)
    {
        $this->order['status'] = 'finalized';

        return $this->order;
    }

    function initPayment($orderId, $payment)
    {
        return [
            'paymentMethod' => 'default',
            'reference' => 'ref123',
            'currency' => 'CHF',
            'amount' => 12.40,
            'status' => 'initiated',
        ];
    }

    function purchaseOrder($id)
    {
        return $this->order;
    }

    function setBroker($orderId, $broker)
    {
        return $this->order;
    }
}