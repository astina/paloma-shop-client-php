<?php

namespace Paloma\Shop\Customers;

use DateTime;
use Paloma\Shop\Common\Address;
use Paloma\Shop\Common\AddressInterface;
use Paloma\Shop\Security\UserDetailsInterface;

class Customer extends CustomerBasics implements CustomerInterface
{
    public static function toBackendData(CustomerInterface $customer, UserDetailsInterface $user = null)
    {
        return [
            'id' => $customer->getId(),
            'emailAddress' => $customer->getEmailAddress(),
            'customerNumber' => $customer->getCustomerNumber(),
            'locale' => $customer->getLocale(),
            'firstName' => $customer->getFirstName(),
            'lastName' => $customer->getLastName(),
            'company' => $customer->getCompany(),
            'gender' => $customer->getGender(),
            'contactAddress' => Address::toAddressArray($customer->getContactAddress()),
            'billingAddress' => Address::toAddressArray($customer->getBillingAddress()),
            'shippingAddress' => Address::toAddressArray($customer->getShippingAddress()),
            'users' => $user
                ? ['id' => $user->getUserId()]
                : null,
        ];
    }

    function getDateOfBirth(): ?DateTime
    {
        return isset($this->data['dateOfBirth'])
            ? DateTime::createFromFormat('Y-m-d', $this->data['dateOfBirth'])
            : null;
    }

    function getContactAddress(): AddressInterface
    {
        return Address::ofData($this->data['contactAddress']);
    }

    function getBillingAddress(): AddressInterface
    {
        return Address::ofData($this->data['billingAddress']);
    }

    function getShippingAddress(): AddressInterface
    {
        return Address::ofData($this->data['shippingAddress']);
    }
}