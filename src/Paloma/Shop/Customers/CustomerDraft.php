<?php

namespace Paloma\Shop\Customers;

use DateTime;

class CustomerDraft implements CustomerDraftInterface
{
    private $emailAddress;

    private $password;

    private $locale;

    private $firstName;

    private $lastName;

    private $gender;

    private $dateOfBirth;

    /**
     * @param string $emailAddress
     * @param string $password
     * @param string $locale
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string $gender
     * @param DateTime|null $dateOfBirth
     */
    public function __construct(string $emailAddress = null, string $password = null,
                                string $locale = null, string $firstName = null, string $lastName = null,
                                string $gender = 'unknown', DateTime $dateOfBirth = null)
    {
        $this->emailAddress = $emailAddress;
        $this->password = $password;
        $this->locale = $locale;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->gender = $gender;
        $this->dateOfBirth = $dateOfBirth;
    }

    public function withLocale(string $locale): CustomerDraftInterface
    {
        return new CustomerDraft(
            $this->emailAddress,
            $this->password,
            $locale,
            $this->firstName,
            $this->lastName,
            $this->gender,
            $this->dateOfBirth
        );
    }

    /**
     * @return string
     */
    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @return DateTime|null
     */
    public function getDateOfBirth(): ?DateTime
    {
        return $this->dateOfBirth;
    }
}