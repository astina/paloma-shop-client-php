<?php

namespace Paloma\Shop\Customers;

interface CustomerBasicsInterface
{
    /**
     * @return string
     */
    function getEmailAddress(): string;

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
}