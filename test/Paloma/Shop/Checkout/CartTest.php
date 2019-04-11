<?php

namespace Paloma\Shop\Checkout;

use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    public function testCartEmpty()
    {
        $cart = Cart::createEmpty();

        $this->assertTrue($cart->isEmpty());
        $this->assertEquals(0, count($cart->getItems()));
    }

    public function testCart()
    {
        $order = json_decode(file_get_contents(__DIR__ . '/checkout_order.json'), true);

        $cart = new Cart($order);

        $this->assertEquals(2, count($cart->getItems()));
        $this->assertEquals(1, count($cart->getModifications()));

        $item1 = $cart->getItems()[0];
        $this->assertEquals('1', $item1->getId());
        $this->assertEquals('00013580_000_4_49', $item1->getSku());
        $this->assertEquals('00013580_200', $item1->getItemNumber());
        $this->assertEquals('Pumps', $item1->getTitle());
        $this->assertEquals('Very nice', $item1->getDescription());
        $this->assertEquals('CHF 199.00', $item1->getItemPrice());
        $this->assertEquals('CHF 199.00', $item1->getUnitPrice());
        $this->assertEquals('CHF 199.00', $item1->getOriginalPrice());
        $this->assertEquals(1, $item1->getQuantity());
        $this->assertEquals(3, $item1->getAvailableQuantity());
        $this->assertEquals('00013580_000_4_49', $item1->getProductVariant()->getSku());
        $this->assertNull($item1->getProduct());
        $this->assertEquals('c800acbe-e963-4bed-a80f-e2c4799350cc.jpg', $item1->getImage()->getName());
        $this->assertEquals(
            'https://demo.paloma.one/images/small/c800acbe-e963-4bed-a80f-e2c4799350cc.jpg',
            $item1->getImage()->getSource('small')->getUrl());
    }

}