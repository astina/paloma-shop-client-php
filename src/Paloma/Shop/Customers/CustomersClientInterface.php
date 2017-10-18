<?php

namespace Paloma\Shop\Customers;

interface CustomersClientInterface
{
    function createAdvertisingPrefs($country, $advertisingPrefs);

    function confirmAdvertisingPrefs($country, $token);

    function authenticate($country, $login);

    function getLoyaltyPrograms($country, $userIdOrEmail);

    function updateLoyaltyPrograms($country, $userIdOrEmail, $program);

    function startPasswordReset($country, $passwordReset);

    function getPasswordResetToken($country, $token);

    function updatePassword($country, $token, $password);

    function register($country, $user);

    function getUser($country, $id);

    function updateUserPartially($country, $id, $user);

    function updateUser($country, $id, $user);

    function getWishList($country, $userId);

    function addWishListItem($country, $userId, $item);

    function deleteWishListItem($country, $userId, $itemId);

    function getOrderStatus($country, $language, $orderNr);

    function getOrders($country, $language, $userId, $pageNr = null, $pageSize = null, $sortOrder = null);

    function getOrder($country, $language, $userId, $orderNr);

    function getOrderReceipt($country, $language, $userId, $orderNr);

    function confirmEmail($country, $token);

}