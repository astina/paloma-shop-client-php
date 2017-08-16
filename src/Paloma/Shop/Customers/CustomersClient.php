<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\BaseClient;
use Psr\Log\LoggerInterface;

class CustomersClient extends BaseClient implements CustomersClientInterface
{
    public function __construct($baseUrl, $apiKey, $debug = false, LoggerInterface $logger = null)
    {
       parent::__construct($baseUrl, $apiKey, $debug, $logger);
    }

    function createAdvertisingPrefs($country, $advertisingPrefs)
    {
        return $this->post($country . '/advertising', null, $advertisingPrefs);
    }

    function confirmAdvertisingPrefs($country, $token)
    {
        return $this->postFormData($country . '/advertising/confirm', null, ['token' => $token]);
    }

    function authenticate($country, $login)
    {
        return $this->post($country . '/authenticate', null, $login);
    }

    function getLoyaltyPrograms($country, $userIdOrEmail)
    {
        return $this->get($country . '/customers/' . $userIdOrEmail . '/loyalty-programs');
    }

    function updateLoyaltyPrograms($country, $userIdOrEmail, $program)
    {
        return $this->post($country . '/customers/' . $userIdOrEmail . '/loyalty-programs', null, $program);
    }

    function startPasswordReset($country, $passwordReset)
    {
        return $this->post($country . '/password-reset', null, $passwordReset);
    }

    function getPasswordResetToken($country, $token)
    {
        return $this->get($country . '/password-reset/' . $token);
    }

    function updatePassword($country, $token, $password)
    {
        return $this->put($country . '/password-reset/' . $token . '/password', null, $password);
    }

    function register($country, $user)
    {
        return $this->post($country . '/users', null, $user);
    }

    function getUser($country, $id)
    {
        return $this->get($country . '/users/' . $id);
    }

    function updateUserPartially($country, $id, $user)
    {
        return $this->patch($country . '/users/' . $id, null, $user);
    }

    function updateUser($country, $id, $user)
    {
        return $this->put($country . '/users/' . $id, null, $user);
    }

    function getWishList($country, $userId)
    {
        return $this->get($country . '/users/' . $userId . '/wish-list/items');
    }

    function addWishListItem($country, $userId, $item)
    {
        return $this->post($country . '/users/' . $userId . '/wish-list/items', null, $item);
    }

    function deleteWishListItem($country, $userId, $itemId)
    {
        return $this->delete($country . '/users/' . $userId . '/wish-list/items/' . $itemId);
    }

    function getOrderStatus($country, $language, $orderNr)
    {
        return $this->get($country . '/' . $language . '/orders/' . $orderNr . '/status');
    }

    function getOrders($country, $language, $userId, $pageNr = null, $pageSize = null, $sortOrder = null)
    {
        $query = [];
        if ($pageNr) {
            $query['pageNr'] = $pageNr;
        }
        if ($pageSize) {
            $query['pageSize'] = $pageSize;
        }
        if ($sortOrder) {
            $query['sortOrder'] = $sortOrder;
        }
        return $this->get($country . '/' . $language . '/users/' . $userId . '/orders', count($query) > 0 ? $query : null);
    }

    function getOrder($country, $language, $userId, $orderNr)
    {
        return $this->get($country . '/' . $language . '/users/' . $userId . '/orders/' . $orderNr);
    }

    function getOrderReceipt($country, $language, $userId, $orderNr)
    {
        return $this->get($country . '/' . $language . '/users/' . $userId . '/orders/' . $orderNr . '/receipt');
    }
}