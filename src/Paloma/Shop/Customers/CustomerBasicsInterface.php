<?php

namespace Paloma\Shop\Customers;

use DateTime;

interface CustomerBasicsInterface
{
    /**
     * @return string Paloma customer ID
     */
    function getId(): ?string;

    /**
     * @return string
     */
    function getEmailAddress(): ?string;

    /**
     * @return string Customer number
     */
    function getCustomerNumber(): ?string;

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
     * @return string|null Company
     */
    function getCompany(): ?string;

    /**
     * @return string Gender, one of 'male', 'female', 'unknown'
     */
    function getGender(): string;

    /**
     * @return DateTime|null Date without time component
     */
    function getDateOfBirth(): ?DateTime;
}