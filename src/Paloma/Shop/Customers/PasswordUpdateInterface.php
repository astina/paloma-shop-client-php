<?php

namespace Paloma\Shop\Customers;

interface PasswordUpdateInterface
{
    function getCurrentPassword(): string;

    function getNewPassword(): string;
}