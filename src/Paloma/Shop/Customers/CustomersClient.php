<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\BaseClient;
use Paloma\Shop\PalomaProfiler;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CustomersClient extends BaseClient implements CustomersClientInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct($baseUrl, $apiKey, $channel, SessionInterface $session = null, LoggerInterface $logger = null, PalomaProfiler $profiler = null)
    {
        parent::__construct($baseUrl, $apiKey, $channel, $logger, $profiler);

        if ($session == null) {
            $session = new Session();
        }

        $this->session = $session;
    }

    function register($customer)
    {
        return $this->post($this->channel . '/customers', null, $customer);
    }

    function getCustomer($customerId)
    {
        return $this->get($this->channel . '/customers/' . $customerId);
    }

    function updateCustomer($customerId, $customer)
    {
        return $this->put($this->channel . '/customers/' . $customerId, null, $customer);
    }

    function updateAddress($customerId, $addressType, $address)
    {
        return $this->put($this->channel . '/customers/' . $customerId . '/addresses/' . $addressType, null, $address);
    }

    function confirmEmailAddress($token)
    {
        return $this->post($this->channel . '/customers/email-address/confirm', null, ['token' => $token]);
    }

    function authenticateUser($username, $password)
    {
        return $this->post($this->channel . '/users/authenticate', null, ['username' => $username, 'password' => $password]);
    }

    function updateUserPassword($password)
    {
        return $this->put($this->channel . '/users/password', null, $password);
    }

    function startUserPasswordReset($emailAddress, $confirmationBaseUrl)
    {
        return $this->post($this->channel . '/users/password-reset', null, ['emailAddress' => $emailAddress, 'confirmationBaseUrl' => $confirmationBaseUrl]);
    }

    function getUserPasswordResetToken($token)
    {
        return $this->get($this->channel . '/users/password-reset/' . $token);
    }

    function setNewPassword($token, $password)
    {
        return $this->put($this->channel . '/users/password-reset/' . $token . '/password', null, ['password' => $password]);
    }

    function updateAdvertisingPreferences($customerId, $advertisingPrefs)
    {
        return $this->put($this->channel . '/customers/' . $customerId . '/advertising', null, $advertisingPrefs);
    }

    function createAdvertisingPrefs($advertisingPrefs)
    {
        return $this->post($this->channel . '/advertising', null, $advertisingPrefs);
    }

    function confirmAdvertisingPrefs($token)
    {
        return $this->postFormData($this->channel . '/advertising/confirm', null, ['token' => $token]);
    }


    function getLoyaltyPrograms($customerId)
    {
        return $this->get($this->channel . '/customers/' . $customerId . '/loyalty-programs');
    }

    function updateLoyaltyPrograms($customerId, $program)
    {
        return $this->post($this->channel . '/customers/' . $customerId . '/loyalty-programs', null, $program);
    }

    function getOrders($locale, $customerId, $pageNr = null, $pageSize = null, $sortOrder = null)
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

        return $this->get($this->channel . '/' . $locale . '/customers/' . $customerId . '/orders', count($query) > 0 ? $query : null);
    }

    function getOrder($locale, $customerId, $orderNr)
    {
        return $this->get($this->channel . '/' . $locale . '/customers/' . $customerId . '/orders/' . $orderNr);
    }

    function getOrderReceipt($locale, $customerId, $orderNr)
    {
        return $this->get($this->channel . '/' . $locale . '/customers/' . $customerId . '/orders/' . $orderNr . '/receipt');
    }

    function getOrderStatus($locale, $orderNr)
    {
        return $this->get($this->channel . '/' . $locale . '/orders/' . $orderNr . '/status');
    }
}