<?php

namespace Paloma\Shop\Customers;

use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    public function testOrder()
    {
        $data = json_decode(file_get_contents(__DIR__ . '/order.json'), true);

        $order = new Order($data);

        $this->assertEquals('100000124', $order->getOrderNumber());
        $this->assertEquals('purchased', $order->getStatus());
        $this->assertEquals(1554491651, $order->getOrderDate()->getTimestamp());
        $this->assertEquals('Hans', $order->getBilling()->getAddress()->getFirstName());
        $this->assertEquals('invoice', $order->getBilling()->getPaymentMethod()->getName());
        $this->assertEquals('Hans2', $order->getShipping()->getAddress()->getFirstName());
        $this->assertEquals('swisspost_priority', $order->getShipping()->getShippingMethod()->getName());
        $this->assertEquals(10, count($order->getItems()));
        $this->assertEquals('CHF 10.00', $order->getItemsPrice());
        $this->assertEquals('CHF 10.00', $order->getShippingPrice());
        $this->assertEquals('CHF -1.00', $order->getReductions()[0]->getPrice());
        $this->assertEquals('CHF 40.00', $order->getSurcharges()[0]->getPrice());
        $this->assertEquals('CHF 1.00', $order->getSurcharges()[1]->getPrice());
        $this->assertEquals('CHF 60.00', $order->getTotalPrice());
        $this->assertEquals('7.7 % MWSt.', $order->getIncludedTaxes()[0]->getDescription());
        $this->assertEquals('CHF 1.52', $order->getIncludedTaxes()[0]->getPrice());

        $item1 = $order->getItems()[0];
        $this->assertEquals('6414242', $item1->getSku());
        $this->assertEquals('6414242', $item1->getCode());
        $this->assertEquals(1, $item1->getQuantity());
        $this->assertEquals('CHF 1.00', $item1->getUnitPrice());
        $this->assertEquals('CHF 1.00', $item1->getItemPrice());
        $this->assertEquals('CHF 1.00', $item1->getOriginalPrice());
        $this->assertEquals('Daily Adult Häppchen mit Pute & Ente', $item1->getTitle());
        $this->assertEquals(
            'https://demo.paloma.one/images/small/f/9/1/4/f914fdfee99354ddda53ea1aca15e0399b20b329_d1ec8de4e67a8fcd7d0e8e1bb067cc2fad26574ad4734ce907d357bb.jpg',
            $item1->getImage()->getSource('small')->getUrl());
    }
}