<?php

namespace Paloma\Shop\Checkout;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class MockRequestStack extends RequestStack
{
    private $session;

    public function __construct()
    {
        $this->session = new Session(new MockArraySessionStorage());
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }
}