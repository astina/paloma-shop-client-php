<?php

namespace Paloma\Shop\Checkout;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $country;

    private $language;

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
     * @param $country string Each country keeps its own cart
     * @param $language string Used to initialize the cart order
     * @param CheckoutClientInterface $checkoutClient
     * @param SessionInterface $session
     */
    public function __construct($country, $language, CheckoutClientInterface $checkoutClient, SessionInterface $session)
    {
        $this->country = $country;
        $this->language = $language;
        $this->checkoutClient = $checkoutClient;
        $this->session = $session;
    }

    public function get()
    {
        return $this->checkoutClient->getOrder($this->getCartId(), $this->language);
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
        if (!isset($customer['country'])) {
            $customer['country'] = $this->country;
        }
        if (!isset($customer['language'])) {
            $customer['language'] = $this->language;
        }
        if (!isset($customer['confirmed'])) {
            $customer['confirmed'] = false;
        }

        return $this->checkoutClient->setUser($this->getCartId(), $customer);
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

        return $this->checkoutClient->initPayment($params);
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

        if (!isset($cartIds[$this->country])) {

            if (!$createOrder) {
                return 0;
            }

            $cart = $this->createCartOrder();

            $cartIds = $cartIds ?: [];
            $cartIds[$this->country] = $cart['id'];

            $this->session->set(self::$CART_ID_VAR, $cartIds);
        }

        return $cartIds[$this->country];
    }

    private function createCartOrder()
    {
        return $this->checkoutClient->createOrder([
            'country' => $this->country,
            'language' => $this->language,
        ]);
    }

    private function clearCartId()
    {
        $cartIds = $this->session->get(self::$CART_ID_VAR);

        if (isset($cartIds[$this->country])) {
            unset($cartIds[$this->country]);
        }

        $this->session->set(self::$CART_ID_VAR, $cartIds);
    }
}