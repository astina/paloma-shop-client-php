<?php

namespace Paloma\Shop\Customers;

class PasswordResetDraft implements PasswordResetDraftInterface
{
    private $emailAddress;

    private $confirmationBaseUrl;

    public function __construct(string $emailAddress, string $confirmationBaseUrl)
    {
        $this->emailAddress = $emailAddress;
        $this->confirmationBaseUrl = $confirmationBaseUrl;
    }

    function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    function getConfirmationBaseUrl(): string
    {
        return $this->confirmationBaseUrl;
    }
}