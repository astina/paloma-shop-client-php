<?php

namespace Paloma\Shop\Security;

use Paloma\Shop\Customers\Customer;
use Paloma\Shop\Customers\CustomerInterface;

class TestPalomaSecurity implements PalomaSecurityInterface
{
    private $user;

    private $customer;

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

        $this->customer = new Customer([
            'id' => $customerId,
            'emailAddress' => 'test@astina.io'
        ]);
    }

    function getUser(): ?UserDetailsInterface
    {
        return $this->user;
    }

    function getCustomer(): ?CustomerInterface
    {
        return $this->customer;
    }
}