<?php

namespace Paloma\Shop\Customers;

interface UserDetailsInterface
{
    /**
     * @return string Username
     */
    function getUsername(): string;

    /**
     * @return string User ID
     */
    function getUserId(): string;

    /**
     * @return string Customer ID
     */
    function getCustomerId(): string;

    /**
     * @return CustomerBasicsInterface
     */
    function getCustomer(): CustomerBasicsInterface;
}