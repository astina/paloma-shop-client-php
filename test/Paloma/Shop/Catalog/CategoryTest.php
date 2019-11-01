<?php

namespace Paloma\Shop\Catalog;

use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testGetMetaDescription()
    {
        $category = new Category([
            'footerText' => '<p>text with &auml;</p>'
        ]);

        $this->assertEquals('text with ä', $category->getMetaDescription());
    }
}