<?php

namespace Paloma\Shop\Customers;

interface CustomerUserInterface
{
    public function getId(): string;

    public function getUsername(): string;

    public function getEmailAddress(): string;

    public function isEnabled(): string;

    public function isConfirmed(): string;

    public function getFirstName(): ?string;

    public function getLastName(): ?string;

    public function getFullName(): string;

    public function getLocale(): string;

    public function getTimeZone(): string;
}