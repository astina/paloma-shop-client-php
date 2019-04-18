<?php

namespace Paloma\Shop\Security;

interface UserProviderInterface
{
    /**
     * @return UserDetailsInterface The currently logged in user (if any)
     */
    function getUser(): ?UserDetailsInterface;
}