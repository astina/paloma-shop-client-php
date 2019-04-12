<?php

namespace Paloma\Shop\Customers;

interface PasswordResetInterface
{
    /**
     * @return string The password reset confirmation token
     */
    function getToken(): string;

    /**
     * @return string The new password
     */
    function getNewPassword(): string;
}