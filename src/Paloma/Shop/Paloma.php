<?php

namespace Paloma\Shop\Paloma\Shop;

use Paloma\Shop\PalomaFactory;
use Paloma\Shop\PalomaProfiler;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Paloma
{
    public static function create($baseUrl, $apiKey, $defaultChannel, $defaultLocale,
                                  SessionInterface $session = null,
                                  LoggerInterface $logger = null,
                                  PalomaProfiler $profiler = null)
    {
        $factory = new PalomaFactory($baseUrl, $apiKey, $defaultChannel, $defaultLocale, $session, $logger, $profiler);

        return $factory->create();
    }
}