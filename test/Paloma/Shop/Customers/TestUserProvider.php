<?php

namespace Paloma\Shop\Customers;

class TestUserProvider implements UserProviderInterface
{
    function getUser(): ?UserDetailsInterface
    {
        return new UserDetails([
            'user' => [
                'id' => '1',
                'username' => 'user',
            ],
            'customer' => [
                'id' => '2',
            ]
        ]);
    }
}