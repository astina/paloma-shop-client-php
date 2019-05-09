<?php

namespace Paloma\Shop;

use Paloma\Shop\Catalog\Catalog;
use Paloma\Shop\Catalog\CatalogInterface;
use Paloma\Shop\Checkout\Checkout;
use Paloma\Shop\Checkout\CheckoutInterface;
use Paloma\Shop\Customers\Customers;
use Paloma\Shop\Customers\CustomersInterface;
use Paloma\Shop\Security\UserProviderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Paloma
{
    private $catalog;

    private $checkout;

    private $customers;

    public function __construct(CatalogInterface $catalog, CheckoutInterface $checkout, CustomersInterface $customers)
    {
        $this->catalog = $catalog;
        $this->checkout = $checkout;
        $this->customers = $customers;
    }

    public function catalog(): CatalogInterface
    {
        return $this->catalog;
    }

    public function checkout(): CheckoutInterface
    {
        return $this->checkout;
    }

    public function customers(): CustomersInterface
    {
        return $this->customers;
    }

    public static function createClient(array $options)
    {
        $factory = new PalomaClientFactory($options);

        return $factory->create();
    }
}
