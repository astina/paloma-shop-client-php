<?php

namespace Paloma\Shop;

use Paloma\Shop\Catalog\CatalogClientInterface;
use Paloma\Shop\Catalog\CatalogTestClient;
use Paloma\Shop\Checkout\CheckoutClientInterface;
use Paloma\Shop\Checkout\CheckoutTestClient;
use Paloma\Shop\Customers\CustomersClientInterface;
use Paloma\Shop\Customers\CustomersTestClient;

class PalomaTestClient extends PalomaClient
{
    public function __construct(CatalogClientInterface $catalogClient = null, CheckoutClientInterface $checkoutClient = null, CustomersClientInterface $customersClient = null)
    {
        parent::__construct(
            $catalogClient ?? new CatalogTestClient(),
            $checkoutClient ?? new CheckoutTestClient(),
            $customersClient ?? new CustomersTestClient()
        );
    }

    public function withCatalog(CatalogClientInterface $catalogClient)
    {
        return new PalomaTestClient($catalogClient, $this->checkout(), $this->customers());
    }

    public function withCheckout(CheckoutClientInterface $checkoutClient)
    {
        return new PalomaTestClient($this->catalog(), $checkoutClient, $this->customers());
    }

    public function withCustomers(CustomersClientInterface $customersClient)
    {
        return new PalomaTestClient($this->catalog(), $this->checkout(), $customersClient);
    }
}