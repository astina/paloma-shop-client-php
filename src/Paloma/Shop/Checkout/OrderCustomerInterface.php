<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\Customers\CustomerBasicsInterface;

interface OrderCustomerInterface extends CustomerBasicsInterface
{
    function getUserId(): ?string;
}