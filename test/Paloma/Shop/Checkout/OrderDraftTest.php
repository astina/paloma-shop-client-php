<?php

namespace Paloma\Shop\Checkout;

use PHPUnit\Framework\TestCase;

class OrderDraftTest extends TestCase
{
    public function testOrderDraft()
    {
        $data = json_decode(file_get_contents(__DIR__ . '/checkout_order.json'), true);

        $order = new OrderDraft($data);

        $this->assertEquals('Muster', $order->getBilling()->getAddress()->getLastName());
        $this->assertEquals('default', $order->getBilling()->getPaymentMethod()->getName());
        $this->assertEquals('Muster', $order->getShipping()->getAddress()->getLastName());
        $this->assertEquals('default', $order->getShipping()->getShippingMethod()->getName());

        $this->assertEquals(2, count($order->getItems()));
        $this->assertEquals(1, count($order->getModifications()));

        $this->assertEquals('CHF 398.00', $order->getItemsPrice());
        $this->assertEquals('CHF 10.00', $order->getShippingPrice());
        $this->assertEquals('CHF -30.00', $order->getReductions()[0]->getPrice());
        $this->assertEquals('CHF 368.52', $order->getNetTotalPrice());
        $this->assertEquals('CHF 398.00', $order->getTotalPrice());
        $this->assertEquals('8 % MwSt.', $order->getIncludedTaxes()[0]->getDescription());
        $this->assertEquals('CHF 29.48', $order->getIncludedTaxes()[0]->getPrice());

        $item1 = $order->getItems()[0];
        $this->assertEquals('1', $item1->getId());
        $this->assertEquals('00013580_000_4_49', $item1->getSku());
        $this->assertEquals('00013580_200', $item1->getItemNumber());
        $this->assertEquals('Pumps', $item1->getTitle());
        $this->assertEquals('Very nice', $item1->getDescription());
        $this->assertEquals('CHF 199.00', $item1->getItemPrice());
        $this->assertEquals('CHF 184.26', $item1->getNetItemPrice());
        $this->assertEquals('CHF 199.00', $item1->getUnitPrice());
        $this->assertEquals('CHF 184.26', $item1->getNetUnitPrice());
        $this->assertEquals('299.00', $item1->getOriginalPrice());
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