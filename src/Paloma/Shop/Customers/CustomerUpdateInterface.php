<?php

namespace Paloma\Shop\Customers;

use DateTime;

interface CustomerUpdateInterface
{
    /**
     * @return string
     */
    function getEmailAddress(): string;

    /**
     * @return string Locale code (de, en, fr, de_CH, ...)
     */
    function getLocale(): string;

    /**
     * @return string|null First name
     */
    function getFirstName(): ?string;

    /**
     * @return string|null Last name
     */
    function getLastName(): ?string;

    /**
     * @return string Gender, one of 'male', 'female', 'unknown'
     */
    function getGender(): string;

    /**
     * @return DateTime|null Date without time component
     */
    function getDateOfBirth(): ?DateTime;
}