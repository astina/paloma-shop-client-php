<?php

namespace Paloma\Shop;

class PalomaTestConfig implements PalomaConfigInterface
{
    function getRegistrationConfirmationBaseUrl(): string
    {
        return 'https://test/register/confirm';
    }
}