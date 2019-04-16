<?php

namespace Paloma\Shop\Customers;

use DateTime;

interface CustomerDraftInterface
{
    /**
     * Email address has to be unique within a channel.
     * A user will be created for this customer with the email address as username,
     *
     * @return string
     */
    function getEmailAddress(): ?string;

    /**
     * @return string User password
     */
    function getPassword(): ?string;

    /**
     * @return string Locale code (de, en, fr, de_CH, ...)
     */
    function getLocale(): ?string;

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
    function getGender(): ?string;

    /**
     * @return DateTime|null Date without time component
     */
    function getDateOfBirth(): ?DateTime;
}