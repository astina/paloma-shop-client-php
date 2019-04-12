<?php

namespace Paloma\Shop\Customers;

class PasswordUpdate implements PasswordUpdateInterface
{
    private $currentPassword;

    private $newPassword;

    public function __construct(string $currentPassword, string $newPassword)
    {
        $this->currentPassword = $currentPassword;
        $this->newPassword = $newPassword;
    }

    function getCurrentPassword(): string
    {
        return $this->currentPassword;
    }

    function getNewPassword(): string
    {
        return $this->newPassword;
    }
}