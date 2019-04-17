<?php

namespace Paloma\Shop;

use Paloma\Shop\Customers\UserDetails;
use Paloma\Shop\Customers\UserDetailsInterface;

class PalomaTestSecurity implements PalomaSecurityInterface
{
    private $user;

    public function __construct()
    {
        $this->user = new UserDetails([
            'user' => [
                'id' => '1',
                'username' => 'user',
            ],
            'customer' => [
                'id' => '2',
            ]
        ]);
    }

    function getUser(): ?UserDetailsInterface
    {
        return $this->user;
    }

    function setAuthenticated(UserDetailsInterface $user): void
    {
        $this->user = $user;
    }
}