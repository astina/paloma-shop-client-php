<?php

namespace Paloma\Shop\Catalog;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Paloma\Shop\Checkout\CheckoutTestClient;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\CategoryNotFound;
use Paloma\Shop\Error\ProductNotFound;
use Paloma\Shop\PalomaTestClient;
use PHPUnit\Framework\TestCase;

class CatalogTest extends TestCase
{
    public function testSearch()
    {
        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient(
            [['itemNumber' => '123', 'variants' => [['sku' => 'sku1']]]]
        )));

        /** @noinspection PhpUnhandledExceptionInspection */
        $productPage = $catalog->search(new SearchRequest(
            'test',
            'query',
            [new SearchFilter('test', ['foo'])]
        ));

        $this->assertNotNull($productPage);
        $this->assertInstanceOf(ProductPageInterface::class, $productPage);
        $this->assertEquals('123', $productPage->getContent()[0]->getItemNumber());
    }

    public function testSearchWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient([], [], $this->createServerException())));

        /** @noinspection PhpUnhandledExceptionInspection */
        $catalog->search(new SearchRequest());
    }

    public function testGetSearchSuggestions()
    {
        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient(
            [['itemNumber' => 'prod1', 'variants' => [['sku' => 'sku1']] ]],
            [['code' => 'cat1',]]
        )));

        /** @noinspection PhpUnhandledExceptionInspection */
        $suggestions = $catalog->getSearchSuggestions('test');

        $this->assertEquals('cat1', $suggestions->getCategories()[0]->getCode());
        $this->assertEquals('prod1', $suggestions->getProducts()[0]->getItemNumber());
    }

    public function testGetSearchSuggestionsWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient([], [], $this->createServerException())));

        /** @noinspection PhpUnhandledExceptionInspection */
        $catalog->getSearchSuggestions('test');
    }

    public function testGetProduct()
    {
        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient(
            [['itemNumber' => '123', 'variants' => [['sku' => 'sku1']] ]]
        )));

        /** @noinspection PhpUnhandledExceptionInspection */
        $product = $catalog->getProduct('123');

        $this->assertEquals('123', $product->getItemNumber());
    }

    public function testGetProductWith404Response()
    {
        $this->expectException(ProductNotFound::class);

        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient([], [], $this->createNotFoundException())));

        /** @noinspection PhpUnhandledExceptionInspection */
        $catalog->getProduct('test');
    }

    public function testGetProductWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient([], [], $this->createServerException())));

        /** @noinspection PhpUnhandledExceptionInspection */
        $catalog->getProduct('test');
    }

    public function testGetSimilarProducts()
    {
        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient(
            [['itemNumber' => '123', 'variants' => [['sku' => 'sku1']] ]]
        )));

        /** @noinspection PhpUnhandledExceptionInspection */
        $productPage = $catalog->getSimilarProducts('123');

        $this->assertEquals('123', $productPage->getContent()[0]->getItemNumber());
    }

    public function testGetSimilarProductsWith404Response()
    {
        $this->expectException(ProductNotFound::class);

        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient([], [], $this->createNotFoundException())));

        /** @noinspection PhpUnhandledExceptionInspection */
        $catalog->getSimilarProducts('test');
    }

    public function testGetSimilarProductsWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient([], [], $this->createServerException())));

        /** @noinspection PhpUnhandledExceptionInspection */
        $catalog->getSimilarProducts('test');
    }

    public function testGetRecommendedProducts()
    {
        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient(
            [['itemNumber' => '123', 'variants' => [['sku' => 'sku1']] ]]
        )));

        /** @noinspection PhpUnhandledExceptionInspection */
        $productPage = $catalog->getRecommendedProducts('123');

        $this->assertEquals('123', $productPage->getContent()[0]->getItemNumber());
    }

    public function testGetRecommendedWith404Response()
    {
        $this->expectException(ProductNotFound::class);

        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient([], [], $this->createNotFoundException())));

        /** @noinspection PhpUnhandledExceptionInspection */
        $catalog->getRecommendedProducts('test');
    }

    public function testGetRecommendedWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient([], [], $this->createServerException())));

        /** @noinspection PhpUnhandledExceptionInspection */
        $catalog->getRecommendedProducts('test');
    }

    public function testGetPurchasedTogether()
    {
        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient()));

        /** @noinspection PhpUnhandledExceptionInspection */
        $products = $catalog->getPurchasedTogether('123');

        $this->assertNotNull($products);
    }

    public function testGetProductsForCartEmpty()
    {
        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient(
            [['itemNumber' => '123', 'variants' => [['sku' => 'sku1']] ]]
        )));

        /** @noinspection PhpUnhandledExceptionInspection */
        $productPage = $catalog->getProductsForCart();

        $this->assertEquals(0, count($productPage->getContent()), 'Cart is empty');
    }

    public function testGetProductsForCartNotEmpty()
    {
        $catalog = new Catalog((new PalomaTestClient())
            ->withCatalog(new CatalogTestClient(
                [['itemNumber' => '123', 'variants' => [['sku' => 'sku1']] ]]
            ))
            ->withCheckout(new CheckoutTestClient(
                [
                    'items' => [[ 'id' => '1' ]]
                ]
            )));

        /** @noinspection PhpUnhandledExceptionInspection */
        $productPage = $catalog->getProductsForCart();

        $this->assertEquals('123', $productPage->getContent()[0]->getItemNumber());
    }

    public function testGetProductsForCartWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        $catalog = new Catalog((new PalomaTestClient())
            ->withCatalog(new CatalogTestClient([], [], $this->createServerException()))
            ->withCheckout(new CheckoutTestClient(
                [
                    'items' => [[ 'id' => '1' ]]
                ]
        )));

        /** @noinspection PhpUnhandledExceptionInspection */
        $catalog->getProductsForCart();
    }

    public function testGetCategories()
    {
        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient(
            [],
            [['code' => 'cat1',]]
        )));

        /** @noinspection PhpUnhandledExceptionInspection */
        $categories = $catalog->getCategories();

        $this->assertEquals('cat1', $categories[0]->getCode());
    }

    public function testGetCategoriesWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient([], [], $this->createServerException())));

        /** @noinspection PhpUnhandledExceptionInspection */
        $catalog->getCategories();
    }

    public function testGetCategory()
    {
        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient(
            [],
            [['code' => 'cat1',]]
        )));

        /** @noinspection PhpUnhandledExceptionInspection */
        $category = $catalog->getCategory('cat1');

        $this->assertEquals('cat1', $category->getCode());
    }

    public function testGetCategoryWith404Response()
    {
        $this->expectException(CategoryNotFound::class);

        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient([], [], $this->createNotFoundException())));

        /** @noinspection PhpUnhandledExceptionInspection */
        $catalog->getCategory('cat1');
    }

    public function testGetCategoryWith503Response()
    {
        $this->expectException(BackendUnavailable::class);

        $catalog = new Catalog((new PalomaTestClient())->withCatalog(new CatalogTestClient([], [], $this->createServerException())));

        /** @noinspection PhpUnhandledExceptionInspection */
        $catalog->getCategory('cat1');
    }

    /**
     * @return ServerException
     */
    private function createServerException(): ServerException
    {
        return new ServerException(
            'test',
            new Request('GET', 'https://example.org'),
            new Response(503)
        );
    }

    /**
     * @return BadResponseException
     */
    private function createNotFoundException(): BadResponseException
    {
        return new BadResponseException(
            'test',
            new Request('GET', 'https://example.org'),
            new Response(404)
        );
    }
}