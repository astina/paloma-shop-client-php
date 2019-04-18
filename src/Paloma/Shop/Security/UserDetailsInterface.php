<?php

namespace Paloma\Shop\Security;

use Paloma\Shop\Customers\CustomerBasicsInterface;

interface UserDetailsInterface
{
    /**
     * @return string Username
     */
    function getUsername(): string;

    /**
     * @return string Customer ID
     */
    function getCustomerId(): string;

    /**
     * @return CustomerBasicsInterface
     */
    function getCustomer(): CustomerBasicsInterface;
}