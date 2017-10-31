<?php

namespace Paloma\Shop\Customers;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class User
{
    private $channel;

    /**
     * @var CustomersClientInterface
     */
    private $customersClient;

    /**
     * @var SessionInterface
     */
    private $session;

    private static $USER_ID_VAR = 'paloma-shop-user-authentication-token';

    /**
     * @param $channel string
     * @param CustomersClientInterface $customersClient
     * @param SessionInterface $session
     */
    public function __construct($channel, CustomersClientInterface $customersClient, SessionInterface $session)
    {
        $this->channel = $channel;
        $this->customersClient = $customersClient;
        $this->session = $session;
    }

    public function getCustomer()
    {
        $authenticationToken = $this->getAuthenticationToken();
        if (!isset($authenticationToken)) {
            return null;
        }

        return $this->customersClient->getCustomer($authenticationToken['customer']['id']);
    }

    public function setAuthenticationTokenInSession($authenticationToken)
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }
        $this->session->set(self::$USER_ID_VAR, $authenticationToken);
    }

    public function logout()
    {
        $this->session->set(self::$USER_ID_VAR, null);
    }

    private function getAuthenticationToken()
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }
        return $this->session->get(self::$USER_ID_VAR);
    }
}