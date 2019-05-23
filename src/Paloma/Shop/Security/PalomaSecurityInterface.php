<?php

namespace Paloma\Shop\Security;

use Paloma\Shop\Customers\CustomerInterface;

interface PalomaSecurityInterface
{
    /**
     * @return UserDetailsInterface The currently logged in user (if any)
     */
    function getUser(): ?UserDetailsInterface;

    /**
     * Use this method if you need the full customer data (e.g. addresses).
     * Use getUser()->getCustomer() for basic customer data.
     *
     * @return CustomerInterface|null The full customer for the currently logged in user (if any)
     */
    function getCustomer(): ?CustomerInterface;
}