<?php

namespace Paloma\Shop\Customers;

class PasswordReset implements PasswordResetInterface
{
    private $token;

    private $newPassword;

    public function __construct(string $token, string $newPassword)
    {
        $this->token = $token;
        $this->newPassword = $newPassword;
    }

    function getToken(): string
    {
        return $this->token;
    }

    function getNewPassword(): string
    {
        return $this->newPassword;
    }
}