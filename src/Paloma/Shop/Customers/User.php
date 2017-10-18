<?php

namespace Paloma\Shop\Customers;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class User
{
    private $country;

    /**
     * @var CustomersClientInterface
     */
    private $customersClient;

    /**
     * @var SessionInterface
     */
    private $session;

    private static $USER_ID_VAR = 'paloma-shop-user-id';

    /**
     * @param $country string
     * @param CustomersClientInterface $customersClient
     * @param SessionInterface $session
     */
    public function __construct($country, CustomersClientInterface $customersClient, SessionInterface $session)
    {
        $this->country = $country;
        $this->customersClient = $customersClient;
        $this->session = $session;
    }

    public function get()
    {
        $userId = $this->getUserId();
        if ($userId === '0') {
            return null;
        }

        return $this->customersClient->getUser($this->country, $userId);
    }


    public function setUserIdInSession($userId)
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }
        $this->session->set(self::$USER_ID_VAR, $userId);
    }

    public function clearUserIdInSession()
    {
        $this->session->set(self::$USER_ID_VAR, null);
    }

    private function getUserId()
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }
        $userId = (string)$this->session->get(self::$USER_ID_VAR);

        return strlen($userId) == 0 ? '0' : $userId;
    }
}