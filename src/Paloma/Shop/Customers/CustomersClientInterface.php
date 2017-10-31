<?php

namespace Paloma\Shop\Customers;

interface CustomersClientInterface
{
    function register($customer);

    function getCustomer($customerId);

    function updateCustomer($customerId, $customer);

    function updateAddress($customerId, $addressType, $address);

    function confirmEmailAddress($token);


    function authenticateUser($username, $password);

    function updateUserPassword($password);

    function startUserPasswordReset($emailAddress, $confirmationBaseUrl);

    function getUserPasswordResetToken($token);

    function setNewPassword($token, $password);


    function updateAdvertisingPreferences($customerId, $advertisingPrefs);

    function createAdvertisingPrefs($advertisingPrefs);

    function confirmAdvertisingPrefs($token);


    function getLoyaltyPrograms($customerId);

    function updateLoyaltyPrograms($customerId, $program);


    function getOrders($locale, $customerId, $pageNr = null, $pageSize = null, $sortOrder = null);

    function getOrder($locale, $customerId, $orderNr);

    function getOrderReceipt($locale, $customerId, $orderNr);

    function getOrderStatus($locale, $orderNr);

}