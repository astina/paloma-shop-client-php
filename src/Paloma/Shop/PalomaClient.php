<?php

namespace Paloma\Shop;

use Paloma\Shop\Catalog\CatalogClient;
use Paloma\Shop\Catalog\CatalogClientInterface;
use Paloma\Shop\Checkout\CheckoutClient;
use Paloma\Shop\Checkout\CheckoutClientInterface;
use Paloma\Shop\Customers\CustomersClient;
use Paloma\Shop\Customers\CustomersClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PalomaClient
{
    /**
     * @var CatalogClientInterface
     */
    private $catalog;

    /**
     * @var CheckoutClientInterface
     */
    private $checkout;

    /**
     * @var CustomersClientInterface
     */
    private $customers;

    public function __construct(CatalogClientInterface $catalog, CheckoutClientInterface $checkout, CustomersClientInterface $customers)
    {
        $this->catalog = $catalog;
        $this->checkout = $checkout;
        $this->customers = $customers;
    }

    /**
     * @return CatalogClientInterface
     */
    public function catalog()
    {
        return $this->catalog;
    }

    /**
     * @return CheckoutClientInterface
     */
    public function checkout()
    {
        return $this->checkout;
    }

    /**
     * @return CustomersClientInterface
     */
    public function customers()
    {
        return $this->customers;
    }
}
