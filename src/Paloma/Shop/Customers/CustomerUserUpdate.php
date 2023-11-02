<?php

namespace Paloma\Shop\Customers;

class CustomerUserUpdate implements CustomerUserUpdateInterface
{
    private ?string $username;
    private ?string $emailAddress;
    private ?bool $enabled;
    private ?string $firstName;
    private ?string $lastName;
    private ?string $locale;
    private ?bool $sendInvitation;
    private ?string $timeZone;

    /**
     * @param string|null $username
     * @param string|null $emailAddress
     * @param string|null $firstName
     * @param string|null $lastName
     * @param bool $enabled
     * @param string|null $locale
     * @param bool $sendInvitation
     * @param string|null $timeZone
     */
    public function __construct(string $username = null,
                                string $emailAddress = null,
                                string $firstName = null,
                                string $lastName = null,
                                bool $enabled = true,
                                string $locale = null,
                                bool $sendInvitation = false,
                                string $timeZone = null)
    {
        $this->username = $username;
        $this->emailAddress = $emailAddress;
        $this->enabled = $enabled;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->locale = $locale;
        $this->sendInvitation = $sendInvitation;
        $this->timeZone = $timeZone;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function isSendInvitation(): ?bool
    {
        return $this->sendInvitation;
    }

    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }
}