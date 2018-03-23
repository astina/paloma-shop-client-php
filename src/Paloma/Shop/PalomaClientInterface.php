<?php


namespace Paloma\Shop;


use Paloma\Shop\Catalog\CatalogClientInterface;
use Paloma\Shop\Checkout\CheckoutClientInterface;
use Paloma\Shop\Customers\CustomersClientInterface;

interface PalomaClientInterface
{

    /**
     * @return CatalogClientInterface
     */
    public function catalog();

    /**
     * @return CheckoutClientInterface
     */
    public function checkout();

    /**
     * @return CustomersClientInterface
     */
    public function customers();

}
