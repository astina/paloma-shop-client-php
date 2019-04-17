<?php

namespace Paloma\Shop;

use Paloma\Shop\Customers\UserDetailsInterface;

interface PalomaSecurityInterface
{
    /**
     * @return UserDetailsInterface The currently logged in user (if any)
     */
    function getUser(): ?UserDetailsInterface;

    /**
     * Sets the given user aus authenticated (e.g. in Symfony security context)
     *
     * @param UserDetailsInterface $user
     */
    function setAuthenticated(UserDetailsInterface $user): void;
}