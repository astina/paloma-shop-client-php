<?php

namespace Paloma\Shop;

use Paloma\Shop\Customers\UserDetails;
use Paloma\Shop\Customers\UserDetailsInterface;

class PalomaTestSecurity implements PalomaSecurityInterface
{
    private $user;

    public function __construct(string $customerId = '1')
    {
        $this->user = new UserDetails([
            'user' => [
                'username' => 'test@astina.io',
            ],
            'customer' => [
                'id' => $customerId,
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