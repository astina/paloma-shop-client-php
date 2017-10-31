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

    /**
     * @return User
     */
    function user()
    {
        return new User($this->channel, $this, $this->session);
    }

    function createAdvertisingPrefs($advertisingPrefs)
    {
        return $this->post($this->channel . '/advertising', null, $advertisingPrefs);
    }

    function confirmAdvertisingPrefs($token)
    {
        return $this->postFormData($this->channel . '/advertising/confirm', null, ['token' => $token]);
    }

    function authenticateUser($credentials)
    {
        $authenticationToken = $this->post($this->channel . '/users/authenticate', null, $credentials);
        //TODO av: test
        $this->user($this->channel)->setUserIdInSession($authenticationToken['user']['id']);
    }

    function getLoyaltyPrograms($customerId)
    {
        return $this->get($this->channel . '/customers/' . $customerId . '/loyalty-programs');
    }

    function updateLoyaltyPrograms($customerId, $program)
    {
        return $this->post($this->channel . '/customers/' . $customerId . '/loyalty-programs', null, $program);
    }

    function startUserPasswordReset($passwordReset)
    {
        return $this->post($this->channel . '/users/password-reset', null, $passwordReset);
    }

    function getUserPasswordResetToken($token)
    {
        return $this->get($this->channel . '/users/password-reset/' . $token);
    }

    function updateUserPassword($token, $password)
    {
        return $this->put($this->channel . '/users/password-reset/' . $token . '/password', null, $password);
    }

    function register($customer)
    {
        $customer = $this->post($this->channel . '/customers', null, $customer);
        $this->user($this->channel)->setUserIdInSession($customer['id']);
    }

    //TODO av: test
    function confirmEmailAddress($token) {
        $confirmToken = $this->getUserPasswordResetToken($token);
        if (isset($confirmToken) && $confirmToken['type'] == 'EMAIL') {
            return $this->post($this->channel . '/customers/' . $confirmToken['user'] . '/confirm-email-address/' . $confirmToken['token']);
        } else {
            return false;
        }
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

    function updateAdvertisingPreferences($customerId, $advertisingPrefs)
    {
        return $this->put($this->channel . '/customers/' . $customerId . '/advertising', null, $advertisingPrefs);
    }

    function getOrderStatus($locale, $orderNr)
    {
        return $this->get($this->channel . '/' . $locale . '/orders/' . $orderNr . '/status');
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
}