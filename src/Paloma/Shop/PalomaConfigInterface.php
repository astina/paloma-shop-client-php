<?php

namespace Paloma\Shop;

interface PalomaConfigInterface
{
    /**
     * A confirmation token will be appended to this URL to be included in the confirmation email sent to the user.
     *
     * @return string
     */
    function getRegistrationConfirmationBaseUrl(): string;
}