<?php

namespace Paloma\Shop\Utils;

use PHPUnit\Framework\TestCase;

class PriceUtilsTest extends TestCase
{
    public function testCalculateReduction()
    {
        $this->assertEquals('-39 %', PriceUtils::calculateReduction('30.00', '49.00'));
        $this->assertEquals('-100 %', PriceUtils::calculateReduction('0.00', '100.00'));
        $this->assertEquals('-1 %', PriceUtils::calculateReduction('99.00', '100.00'));
        $this->assertNull(PriceUtils::calculateReduction('9999.00', '10000.00'));
        $this->assertNull(PriceUtils::calculateReduction('10.00', '10.00'));
        $this->assertNull(PriceUtils::calculateReduction('11.00', '10.00'));
        $this->assertNull(PriceUtils::calculateReduction('11.00', null));
        $this->assertNull(PriceUtils::calculateReduction(null, '1.00'));
    }
}