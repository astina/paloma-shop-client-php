<?php

namespace Paloma\Shop\Security;

class TestUserProvider implements UserProviderInterface
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
                'emailAddress' => 'test@astina.io',
            ]
        ]);
    }

    function getUser(): ?UserDetailsInterface
    {
        return $this->user;
    }
}