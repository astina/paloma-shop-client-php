<?php

namespace Paloma\Shop;

class PalomaTestConfig implements PalomaConfigInterface
{
    function getRegistrationConfirmationBaseUrl(): string
    {
        return 'https://test/register/confirm';
    }

    function getPasswordResetConfirmationBaseUrl(): string
    {
        return 'https://test/password/reset/confirm';
    }
}