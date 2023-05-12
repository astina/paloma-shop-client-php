<?php

namespace Paloma\Shop\Checkout;

use Exception;
use Paloma\Shop\Customers\CustomerInterface;
use Paloma\Shop\Security\UserDetailsInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CheckoutTestClient implements CheckoutClientInterface
{
    private $order;

    private $requestStack;

    private $exception;

    private $exceptionForMethods;

    public function __construct(array $order = null, Exception $exception = null, array $exceptionForMethods = [])
    {
        $this->requestStack = new MockRequestStack();

        $this->order = $order;

        if ($order) {
            $this->requestStack->getSession()->set('paloma-cart-id', ['test' => 'cart1']);
        }

        $this->exception = $exception;
        $this->exceptionForMethods = $exceptionForMethods;
    }

    private function throwException($method)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        if (isset($this->exceptionForMethods[$method])) {
            throw $this->exceptionForMethods[$method];
        }
    }

    /**
     * @param CustomerInterface|null $customer
     * @param UserDetailsInterface|null $user
     * @return CheckoutOrder
     * @throws Exception
     */
    function checkoutOrder(CustomerInterface $customer = null, UserDetailsInterface $user = null)
    {
        $this->throwException(__FUNCTION__);
        return new CheckoutOrder('test', 'de_CH', $this, $this->requestStack, $customer, $user);
    }

    function createOrder($order)
    {
        $this->throwException(__FUNCTION__);
        return $order ?? $this->order;
    }

    function getOrder($id, $languageCode = null)
    {
        $this->throwException(__FUNCTION__);
        return $this->order;
    }

    function deleteOrder($id)
    {
        $this->throwException(__FUNCTION__);
    }

    function addOrderItem($orderId, $item)
    {
        $this->throwException(__FUNCTION__);
        return $this->order;
    }

    function updateOrderItem($orderId, $itemId, $item)
    {
        $this->throwException(__FUNCTION__);
        return $this->order;
    }

    function deleteOrderItem($orderId, $itemId)
    {
        $this->throwException(__FUNCTION__);
        return $this->order;
    }

    function setCustomer($orderId, $customer)
    {
        $this->throwException(__FUNCTION__);
        return $this->order;
    }

    function setAddresses($orderId, $addresses)
    {
        $this->throwException(__FUNCTION__);
        return $this->order;
    }

    function getShippingMethods($orderId)
    {
        $this->throwException(__FUNCTION__);
        return [
            [ 'name' => 'method1' ],
            [ 'name' => 'method2' ],
        ];
    }

    function getShippingMethodOptions($orderId, $methodName, $from = null, $until = null)
    {
        $this->throwException(__FUNCTION__);
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
        $this->throwException(__FUNCTION__);
        return $this->order;
    }

    function getPaymentMethods($orderId)
    {
        $this->throwException(__FUNCTION__);
        return [
            [ 'name' => 'method1', 'type' => 'invoice' ],
            [ 'name' => 'method2', 'type' => 'electronic', 'provider' => 'datatrans' ],
        ];
    }

    function setPaymentMethod($orderId, $method)
    {
        $this->throwException(__FUNCTION__);
        return $this->order;
    }

    function addCoupon($orderId, $coupon)
    {
        $this->throwException(__FUNCTION__);
        return $this->order;
    }

    function deleteCoupon($orderId, $code)
    {
        $this->throwException(__FUNCTION__);
        return $this->order;
    }

    function finalizeOrder($id)
    {
        $this->throwException(__FUNCTION__);

        $this->order['status'] = 'finalized';

        return $this->order;
    }

    function initPayment($orderId, $payment)
    {
        $this->throwException(__FUNCTION__);
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
        $this->throwException(__FUNCTION__);
        return $this->order;
    }

    function setBroker($orderId, $broker)
    {
        $this->throwException(__FUNCTION__);
        return $this->order;
    }
}