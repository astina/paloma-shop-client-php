<?php

namespace Paloma\Shop\Checkout;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $channel;

    private $locale;

    /**
     * @var CheckoutClientInterface
     */
    private $checkoutClient;

    /**
     * @var SessionInterface
     */
    private $session;

    private static $CART_ID_VAR = 'paloma-cart-id';

    /**
     * @param $channel string Each channel keeps its own cart
     * @param $locale string Used to initialize the cart order
     * @param CheckoutClientInterface $checkoutClient
     * @param SessionInterface $session
     */
    public function __construct($channel, $locale, CheckoutClientInterface $checkoutClient, SessionInterface $session)
    {
        $this->locale = $locale;
        $this->channel = $channel;
        $this->checkoutClient = $checkoutClient;
        $this->session = $session;
    }

    public function get()
    {
        return $this->checkoutClient->getOrder($this->getCartId(), $this->locale);
    }

    public function addItem($sku, $quantity = 1)
    {
        return $this->checkoutClient->addOrderItem($this->getCartId(), ['sku' => $sku, 'quantity' => $quantity]);
    }

    public function updateQuantity($itemId, $quantity)
    {
        return $this->checkoutClient->updateOrderItem($this->getCartId(), $itemId, ['quantity' => $quantity]);
    }

    public function removeItem($itemId)
    {
        return $this->checkoutClient->deleteOrderItem($this->getCartId(), $itemId);
    }

    /**
     * Returns the number if order items
     */
    public function itemsCount()
    {
        $cartId = $this->getCartId(false);

        if (!$cartId) {
            return 0;
        }

        $order = $this->checkoutClient->getOrder($cartId);

        return count($order['items']);
    }

    /**
     * Returns the number of order items times quantities
     */
    public function unitsCount()
    {
        $cartId = $this->getCartId(false);

        if (!$cartId) {
            return 0;
        }

        $order = $this->checkoutClient->getOrder($cartId);

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

        return $this->checkoutClient->setCustomer($this->getCartId(), $customer);
    }

    public function setAddresses($billingAddress, $shippingAddress)
    {
        return $this->checkoutClient->setAddresses($this->getCartId(), [
            'billingAddress' => $billingAddress,
            'shippingAddress' => $shippingAddress,
        ]);
    }

    public function finalize()
    {
        return $this->checkoutClient->finalizeOrder($this->getCartId());
    }

    public function initPayment($params)
    {
        $orderId = $this->getCartId();

        $this->checkoutClient->finalizeOrder($orderId);

        $params['order'] = $orderId;

        return $this->checkoutClient->initPayment($orderId, $params);
    }

    public function purchase()
    {
        $order = $this->checkoutClient->purchaseOrder($this->getCartId());

        $this->clearCartId();

        return $order;
    }

    private function getCartId($createOrder = true)
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }

        $cartIds = $this->session->get(self::$CART_ID_VAR);

        if (!isset($cartIds[$this->channel])) {

            if (!$createOrder) {
                return 0;
            }

            $cart = $this->createCartOrder();

            $cartIds = $cartIds ?: [];
            $cartIds[$this->channel] = $cart['id'];

            $this->session->set(self::$CART_ID_VAR, $cartIds);
        }

        return $cartIds[$this->channel];
    }

    private function createCartOrder()
    {
        return $this->checkoutClient->createOrder([
            'channel' => $this->channel,
            'locale' => $this->locale,
        ]);
    }

    private function clearCartId()
    {
        $cartIds = $this->session->get(self::$CART_ID_VAR);

        if (isset($cartIds[$this->channel])) {
            unset($cartIds[$this->channel]);
        }

        $this->session->set(self::$CART_ID_VAR, $cartIds);
    }
}