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

class Paloma
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

    public static function create($baseUrl, $channel, $apiKey, SessionInterface $session = null, LoggerInterface $logger = null, PalomaProfiler $profiler = null)
    {
        if (!preg_match('/\/$/', $baseUrl)) {
            $baseUrl = $baseUrl . '/';
        }

        return new Paloma(
            new CatalogClient($baseUrl . 'catalog/v2/', $channel, $apiKey, $logger, $profiler),
            new CheckoutClient($baseUrl . 'checkout/v2/', $channel, $apiKey, $session, $logger, $profiler),
            new CustomersClient($baseUrl . 'customers/v2/', $channel, $apiKey, $session, $logger, $profiler)
        );
    }

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