<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\FileResponse;
use Psr\Http\Message\StreamInterface;

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

    function addressCompleteHouse($country, $zipCode, $street, $house);

    function addressCompleteStreet($country, $zipCode, $street);

    function addressCompleteZip($country, $zipCity);

    function addressCompleteStreetAndHouse($country, $zipCode, $streetAndHouse);

    function addressValidate($address);

    function getWatchlists($userId);

    function createWatchlist($userId, $watchlistName = null);

    function deleteWatchlist($userId, $watchlistId);

    function getWatchlist($userId, $watchlistId, $locale = null);

    function updateWatchlist($userId, $watchlistId, $watchlist);

    function removeWatchlistArticle($userId, $watchlistId, $sku);

    function addWatchlistArticle($userId, $watchlistId, $sku, $quantity = null);

    function replaceWatchlistArticle($userId, $watchlistId, $oldSku, $newSku);

    /**
     * @return FileResponse
     */
    function exportWatchlists($userId);

    function importWatchlists($userId, StreamInterface $watchlist, $watchlistContentType);

    /**
     * @return FileResponse
     */
    function exportWatchlist($userId, $watchlistId);
}
