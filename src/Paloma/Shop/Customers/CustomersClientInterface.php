<?php

namespace Paloma\Shop\Customers;

interface CustomersClientInterface
{
    //TODO av: dedicated user, adv,... client ??
    function createAdvertisingPrefs($advertisingPrefs);

    function confirmAdvertisingPrefs($token);

    function authenticateUser($username, $password);

    function getLoyaltyPrograms($customerId);

    function updateLoyaltyPrograms($customerId, $program);

    function startUserPasswordReset($passwordReset);

    function getUserPasswordResetToken($token);

    function updateUserPassword($token, $password);

    function register($customer);

    function getCustomer($customerId);

    function updateCustomer($customerId, $customer);

    function updateAddress($customerId, $addressType, $address);

    function updateAdvertisingPreferences($customerId, $advertisingPrefs);

    function getOrderStatus($locale, $orderNr);

    function getOrders($locale, $customerId, $pageNr = null, $pageSize = null, $sortOrder = null);

    function getOrder($locale, $customerId, $orderNr);

    function getOrderReceipt($locale, $customerId, $orderNr);

    function confirmEmailAddress($token);

}