<?php

namespace Paloma\Shop\Customers;

interface PasswordResetDraftInterface
{
    /**
     * @return string Email address
     */
    function getEmailAddress(): string;

    /**
     * A confirmation token will be appended to this URL to be included in the confirmation email sent to the user.
     *
     * @return string
     */
    function getConfirmationBaseUrl(): string;
}