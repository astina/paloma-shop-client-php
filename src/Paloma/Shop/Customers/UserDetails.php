<?php

namespace Paloma\Shop\Customers;

use InvalidArgumentException;

class UserDetails implements UserDetailsInterface
{
    private $data;

    public function __construct(array $data)
    {
        if (!isset($data['user'])) {
            throw new InvalidArgumentException('user data missing');
        }
        if (!isset($data['customer'])) {
            throw new InvalidArgumentException('customer data missing');
        }

        $this->data = $data;
    }

    function getUsername(): string
    {
        return $this->data['user']['username'];
    }

    function getUserId(): string
    {
        return $this->data['user']['id'];
    }

    function getCustomerId(): string
    {
        return $this->data['customer']['id'];
    }

    function getCustomer(): CustomerBasicsInterface
    {
        return new CustomerBasics($this->data['customer']);
    }
}