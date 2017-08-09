<?php

namespace Paloma\Shop;

use Paloma\Shop\Catalog\CatalogClient;
use PHPUnit\Framework\TestCase;

class PalomaTest extends TestCase
{
    public function testInit()
    {
        $paloma = new Paloma(new CatalogClient('http://localhost:8187/api/', 'test'));

        $categories = $paloma->catalog()->categories('ch', 'de');

        $this->assertNotNull($categories);

        $results = $paloma->catalog()->search('ch', 'de', ['category' => 1]);
    }
}