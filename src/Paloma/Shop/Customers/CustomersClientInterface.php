<?php

namespace Paloma\Shop\Customers;

interface CustomersClientInterface
{
    function register($customer);

    function getCustomer($customerId);

    function updateCustomer($customerId, $customer);

    function updateAddress($customerId, $addressType, $address);

    function confirmEmailAddress($token);

    function exists($emailAddress);

    function authenticateUser($username, $password);

    function updateUserPassword($password);

    function startUserPasswordReset($emailAddress, $confirmationBaseUrl);

    function getUserPasswordResetToken($token);

    function finishUserPasswordReset($token, $password);

    function updateAdvertisingPreferences($customerId, $advertisingPrefs);

    function createAdvertisingPrefs($advertisingPrefs);

    function confirmAdvertisingPrefs($token);

    function getLoyaltyPrograms($customerId);

    function updateLoyaltyPrograms($customerId, $program);

    function getOrders($customerId, $pageNr = null, $pageSize = null, $sortOrder = null);

    function getOrder($customerId, $orderNr);

    function getOrderReceipt($customerId, $orderNr);

    function getOrderStatus($orderNr);

    function getItemCodesPurchasedTogether($itemCode, $max = 5);

    function addressCompleteHouse($country, $zipCode, $street, $house);

    function addressCompleteStreet($country, $zipCode, $street);

    function addressCompleteZip($country, $zipCity);

    function addressCompleteStreetAndHouse($country, $zipCode, $streetAndHouse);

    function addressValidate($address);
}
