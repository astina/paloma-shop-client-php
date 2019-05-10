<?php

namespace Paloma\Shop\Catalog;

use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testProduct()
    {
        $product = new Product([
            'itemNumber' => 'itemNumber',
            'name' => 'name',
            'slug' => 'slug',
            'description' => 'description',
            'shortDescription' => 'shortDescription',
            'variants' => [
                [
                    'sku' => 'sku',
                    'name' => 'name',
                    'pricing' => [
                        'currency' => 'currency',
                        'grossPriceFormatted' => 'grossPriceFormatted',
                        'originalGrossPriceFormatted' => 'originalGrossPriceFormatted',
                        'taxes' => [
                            'vat' => [
                                'rateFormatted' => 'rateFormatted'
                            ]
                        ]
                    ],
                    'options' => [
                        [
                            'option' => 'option',
                            'label' => 'label',
                            'value' => 'value',
                        ]
                    ],
                    'attributes' => [
                        'type' => [
                            'type' => 'type',
                            'label' => 'label',
                            'value' => 'value',
                            'display' => 'product',
                        ],
                        'hidden' => [
                            'type' => 'hidden',
                            'label' => 'label',
                            'value' => 'value',
                            'display' => 'none',
                        ]
                    ],
                    'images' => [
                        [
                            'name' => 'name',
                            'sources' => [
                                [
                                    'size' => 'size',
                                    'url' => 'url',
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'master' => [
                'pricing' => [
                    'currency' => 'currency',
                    'grossPriceFormatted' => 'grossPriceFormatted',
                    'originalGrossPriceFormatted' => 'originalGrossPriceFormatted',
                    'taxes' => [
                        'vat' => [
                            'rateFormatted' => 'rateFormatted'
                        ]
                    ]
                ],
                'attributes' => [
                    'type' => [
                        'type' => 'type',
                        'label' => 'label',
                        'value' => 'value',
                        'display' => 'product',
                    ],
                    'hidden' => [
                        'type' => 'hidden',
                        'label' => 'label',
                        'value' => 'value',
                        'display' => 'none',
                    ]
                ],
                'images' => [
                    [
                        'name' => 'name',
                        'sources' => [
                            [
                                'size' => 'size',
                                'url' => 'url',
                            ]
                        ]
                    ]
                ],
            ],
        ]);

        $this->assertEquals('itemNumber', $product->getItemNumber());
        $this->assertEquals('name', $product->getName());
        $this->assertEquals('slug', $product->getSlug());
        $this->assertEquals('description', $product->getDescription());
        $this->assertEquals('shortDescription', $product->getShortDescription());
        $this->assertEquals('currency grossPriceFormatted', $product->getBasePrice());
        $this->assertEquals('originalGrossPriceFormatted', $product->getOriginalBasePrice());
        $this->assertEquals('rateFormatted', $product->getTaxRate());
        $this->assertTrue($product->isTaxIncluded());

        $variant = $product->getVariants()[0];
        $this->assertEquals('sku', $variant->getSku());
        $this->assertEquals('name', $variant->getName());
        $this->assertEquals('currency grossPriceFormatted', $variant->getPrice());
        $this->assertEquals('originalGrossPriceFormatted', $variant->getOriginalPrice());
        $this->assertEquals('rateFormatted', $variant->getTaxRate());
        $this->assertTrue($variant->isTaxIncluded());
        $this->assertEquals(1, count($variant->getOptions()));
        $this->assertEquals(1, count($variant->getAttributes()));
        $this->assertEquals(1, count($variant->getImages()));
        $this->assertEquals('name', $variant->getFirstImage()->getName());
        $this->assertEquals('url', $variant->getFirstImage()->getSource('size')->getUrl());

        $option = $variant->getOptions()[0];
        $this->assertEquals('option', $option->getOption());
        $this->assertEquals('label', $option->getLabel());
        $this->assertEquals('value', $option->getValue());

        $this->assertEquals('label', $variant->getAttribute('type')->getLabel());
        $this->assertNull($variant->getAttribute('hidden'));

        $this->assertEquals(1, count($product->getAttributes()));
        $this->assertEquals('label', $product->getAttribute('type')->getLabel());
        $this->assertNull($product->getAttribute('hidden'));

        $this->assertEquals(1, count($product->getImages()));
        $this->assertEquals('name', $product->getFirstImage()->getName());
        $this->assertEquals('url', $product->getFirstImage()->getSource('size')->getUrl());
    }
}