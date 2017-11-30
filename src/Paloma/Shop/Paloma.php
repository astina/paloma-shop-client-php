<?php

namespace Paloma\Shop;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Paloma
{
    public static function create($baseUrl, $apiKey, $defaultChannel, $defaultLocale,
                                  SessionInterface $session = null,
                                  LoggerInterface $logger = null,
                                  $successLogFormat = null,
                                  $errorLogFormat = null,
                                  PalomaProfiler $profiler = null)
    {
        $factory = new PalomaFactory($baseUrl, $apiKey, $defaultChannel, $defaultLocale, $session, $logger, $successLogFormat, $errorLogFormat, $profiler);

        return $factory->create();
    }
}
