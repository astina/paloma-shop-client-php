<?php

namespace Paloma\Shop\Checkout;

use GuzzleHttp\Exception\ClientException;
use Paloma\Shop\Customers\Customer;
use Paloma\Shop\Customers\CustomerInterface;
use Paloma\Shop\Security\UserDetailsInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CheckoutOrder
{
    private string $channel;

    private string $locale;

    private CheckoutClientInterface $checkoutClient;

    private RequestStack $requestStack;

    private ?CustomerInterface $customer;

    private ?UserDetailsInterface $user;

    private ?array $order;

    /**
     * @param $channel string Each channel keeps its own cart
     * @param $locale string Used to initialize the cart order
     * @param CheckoutClientInterface $checkoutClient
     * @param RequestStack $requestStack
     * @param CustomerInterface|null $customer
     * @param UserDetailsInterface|null $user
     */
    public function __construct(string                  $channel,
                                string                  $locale,
                                CheckoutClientInterface $checkoutClient,
                                RequestStack            $requestStack,
                                CustomerInterface       $customer = null,
                                UserDetailsInterface    $user = null)
    {
        $this->channel = $channel;
        $this->locale = $locale;
        $this->checkoutClient = $checkoutClient;
        $this->requestStack = $requestStack;
        $this->customer = $customer;
        $this->user = $user;
    }

    private static string $CART_ID_VAR = 'paloma-cart-id';

    public function get()
    {
        return $this->checkedCall(function () {
            $this->order = $this->order ?: $this->checkoutClient->getOrder($this->getCartId(), $this->locale);
            return $this->order;
        });
    }

    public function addItem($sku, $quantity = 1)
    {
        return $this->checkedCall(function() use ($sku, $quantity) {
            $this->order = $this->checkoutClient->addOrderItem($this->getCartId(), ['sku' => $sku, 'quantity' => $quantity]);
            return $this->order;
        });
    }

    public function updateQuantity($itemId, $quantity)
    {
        return $this->checkedCall(function () use ($itemId, $quantity) {
            $this->order = $this->checkoutClient->updateOrderItem($this->getCartId(), $itemId, ['quantity' => $quantity]);
            return $this->order;
        });
    }

    public function removeItem($itemId)
    {
        return $this->checkedCall(function () use ($itemId) {
            $this->order = $this->checkoutClient->deleteOrderItem($this->getCartId(), $itemId);
            return $this->order;
        });
    }

    /**
     * Returns the number if order items
     */
    public function itemsCount(): int
    {
        $cartId = $this->getCartId(false);

        if (!$cartId) {
            return 0;
        }

        $order = $this->get();
        return count($order['items']);
    }

    /**
     * Returns the number of order items times quantities
     */
    public function unitsCount(): int
    {
        $cartId = $this->getCartId(false);

        if (!$cartId) {
            return 0;
        }

        $order = $this->get();
        $count = 0;
        foreach ($order['items'] as $item) {
            $count += $item['quantity'];
        }

        return $count;
    }

    public function setCustomer($customer)
    {
        if (!isset($customer['channel'])) {
            $customer['channel'] = $this->channel;
        }
        if (!isset($customer['locale'])) {
            $customer['locale'] = $this->locale;
        }
        if (!isset($customer['confirmed'])) {
            $customer['confirmed'] = false;
        }

        return $this->checkedCall(function () use ($customer) {
            $this->order = $this->checkoutClient->setCustomer($this->getCartId(), $customer);
            return $this->order;
        });
    }

    public function setAddresses($billingAddress, $shippingAddress)
    {
        return $this->checkedCall(function () use ($billingAddress, $shippingAddress) {
            $this->order = $this->checkoutClient->setAddresses($this->getCartId(), [
                'billingAddress' => $billingAddress,
                'shippingAddress' => $shippingAddress,
            ]);
            return $this->order;
        });
    }

    public function finalize()
    {
        return $this->checkedCall(function () {
            $this->order = $this->checkoutClient->finalizeOrder($this->getCartId());
            return $this->order;
        });
    }

    public function getPaymentMethods()
    {
        return $this->checkedCall(function () {
            return $this->checkoutClient->getPaymentMethods($this->getCartId());
        });
    }

    public function setPaymentMethod($paymentMethod)
    {
        return $this->checkedCall(function () use ($paymentMethod) {
            return $this->checkoutClient->setPaymentMethod($this->getCartId(), $paymentMethod);
        });
    }

    public function initPayment($params)
    {
        $orderId = $this->getCartId();

        $this->checkedCall(function () use ($orderId) {
            $this->checkoutClient->finalizeOrder($orderId);
        });

        $params['order'] = $orderId;

        return $this->checkoutClient->initPayment($orderId, $params);
    }

    public function purchase()
    {
        $order = $this->checkedCall(function () {
            return $this->checkoutClient->purchaseOrder($this->getCartId());
        });

        $this->clearCartId();

        return $order;
    }

    public function existsInSession(): bool
    {
        return boolval($this->getCartId(false));
    }

    private function getCartId($createOrder = true)
    {
        // Do not force a session to be created unless needed
        $session = $this->requestStack->getSession();
        if (!$createOrder && !$session->isStarted()) {
            return null;
        }

        $cartIds = $session->get(self::$CART_ID_VAR);

        if (!isset($cartIds[$this->channel])) {

            if (!$createOrder) {
                return null;
            }

            $cart = $this->createCartOrder();

            $cartIds = $cartIds ?: [];
            $cartIds[$this->channel] = $cart['id'];

            $session->set(self::$CART_ID_VAR, $cartIds);
        }

        return $cartIds[$this->channel];
    }

    private function createCartOrder()
    {
        return $this->checkedCall(function () {

            $customer = $this->customer
                ? Customer::toBackendData($this->customer, $this->user)
                : null;

            $this->order = $this->checkoutClient->createOrder([
                'channel' => $this->channel,
                'locale' => $this->locale,
                'customer' => $customer,
            ]);

            return $this->order;
        });
    }

    private function clearCartId()
    {
        $session = $this->requestStack->getSession();
        $cartIds = $session->get(self::$CART_ID_VAR);

        if (isset($cartIds[$this->channel])) {
            unset($cartIds[$this->channel]);
        }

        $session->set(self::$CART_ID_VAR, $cartIds);
        $this->order = null;
    }

    private function checkedCall(callable $call)
    {
        try {
            return $call();
        } catch (ClientException $e) {
            if ($e->getCode() == 404) {
                $this->clearCartId();
            }
            throw $e;
        }
    }
}
