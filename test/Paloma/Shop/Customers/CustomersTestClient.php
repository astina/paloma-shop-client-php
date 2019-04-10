<?php

namespace Paloma\Shop\Customers;

class CustomersTestClient implements CustomersClientInterface
{

    function register($customer)
    {
        // TODO: Implement register() method.
    }

    function getCustomer($customerId)
    {
        // TODO: Implement getCustomer() method.
    }

    function updateCustomer($customerId, $customer)
    {
        // TODO: Implement updateCustomer() method.
    }

    function updateAddress($customerId, $addressType, $address)
    {
        // TODO: Implement updateAddress() method.
    }

    function confirmEmailAddress($token)
    {
        // TODO: Implement confirmEmailAddress() method.
    }

    function exists($emailAddress)
    {
        // TODO: Implement exists() method.
    }

    function authenticateUser($username, $password)
    {
        // TODO: Implement authenticateUser() method.
    }

    function updateUserPassword($password)
    {
        // TODO: Implement updateUserPassword() method.
    }

    function startUserPasswordReset($emailAddress, $confirmationBaseUrl)
    {
        // TODO: Implement startUserPasswordReset() method.
    }

    function getUserPasswordResetToken($token)
    {
        // TODO: Implement getUserPasswordResetToken() method.
    }

    function finishUserPasswordReset($token, $password)
    {
        // TODO: Implement finishUserPasswordReset() method.
    }

    function updateAdvertisingPreferences($customerId, $advertisingPrefs)
    {
        // TODO: Implement updateAdvertisingPreferences() method.
    }

    function createAdvertisingPrefs($advertisingPrefs)
    {
        // TODO: Implement createAdvertisingPrefs() method.
    }

    function confirmAdvertisingPrefs($token)
    {
        // TODO: Implement confirmAdvertisingPrefs() method.
    }

    function getLoyaltyPrograms($customerId)
    {
        // TODO: Implement getLoyaltyPrograms() method.
    }

    function updateLoyaltyPrograms($customerId, $program)
    {
        // TODO: Implement updateLoyaltyPrograms() method.
    }

    function getOrders($customerId, $pageNr = null, $pageSize = null, $sortOrder = null)
    {
        // TODO: Implement getOrders() method.
    }

    function getOrder($customerId, $orderNr)
    {
        // TODO: Implement getOrder() method.
    }

    function getOrderReceipt($customerId, $orderNr)
    {
        // TODO: Implement getOrderReceipt() method.
    }

    function getOrderStatus($orderNr)
    {
        // TODO: Implement getOrderStatus() method.
    }

    function addressCompleteHouse($country, $zipCode, $street, $house)
    {
        // TODO: Implement addressCompleteHouse() method.
    }

    function addressCompleteStreet($country, $zipCode, $street)
    {
        // TODO: Implement addressCompleteStreet() method.
    }

    function addressCompleteZip($country, $zipCity)
    {
        // TODO: Implement addressCompleteZip() method.
    }

    function addressCompleteStreetAndHouse($country, $zipCode, $streetAndHouse)
    {
        // TODO: Implement addressCompleteStreetAndHouse() method.
    }

    function addressValidate($address)
    {
        // TODO: Implement addressValidate() method.
    }
}