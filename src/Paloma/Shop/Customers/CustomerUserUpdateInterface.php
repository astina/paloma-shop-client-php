<?php

namespace Paloma\Shop\Customers;

interface CustomerUserUpdateInterface
{
    public function getUsername(): ?string;

    public function getEmailAddress(): ?string;

    public function isEnabled(): ?bool;

    public function getFirstName(): ?string;

    public function getLastName(): ?string;

    public function getLocale(): ?string;

    public function isSendInvitation(): ?bool;

    public function getTimeZone(): ?string;
}