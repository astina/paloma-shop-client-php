<?php

namespace Paloma\Shop\Checkout;

use DateTime;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

class ShippingMethodOptionsTest extends TestCase
{
    public function testShippingMethodOptions()
    {
        $options = new ShippingMethodOptions([
            'validUntil' => '2019-04-11T08:06:41.875+0000',
            'delivery' => [
                [ 'targetDate' => '2019-04-12' ],
                [ 'targetDate' => '2019-04-13' ],
                [ 'targetDate' => '2019-04-14' ],
            ]
        ]);

        $validUntil = $options->getValidUntil();

        $this->assertInstanceOf(DateTime::class, $validUntil);
        $this->assertEquals('2019-04-11 08:06:41', $validUntil->format('Y-m-d H:i:s'));
        $this->assertEquals(new DateTimeZone('+00:00'), $validUntil->getTimezone());

        $this->assertEquals(3, count($options->getDelivery()));

        $targetDate = $options->getDelivery()[0]->getTargetDate();
        $this->assertInstanceOf(DateTime::class, $targetDate);
        $this->assertEquals('2019-04-12', $targetDate->format('Y-m-d'));
    }
}