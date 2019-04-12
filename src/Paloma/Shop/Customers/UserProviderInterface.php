<?php

namespace Paloma\Shop\Customers;

interface UserProviderInterface
{
    /**
     * @return UserDetailsInterface The currently logged in user (if any)
     */
    function getUser(): ?UserDetailsInterface;
}