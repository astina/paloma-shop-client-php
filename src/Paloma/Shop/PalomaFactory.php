<?php

namespace Paloma\Shop;

use Paloma\Shop\Catalog\CatalogClient;
use Paloma\Shop\Checkout\CheckoutClient;
use Paloma\Shop\Customers\CustomersClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PalomaFactory
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $defaultChannel;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var string
     */
    private $successLogFormat;
    /**
     * @var string
     */
    private $errorLogFormat;
    /**
     * @var PalomaProfiler
     */
    private $profiler;

    /**
     * @param string $baseUrl API base URL
     * @param string $apiKey API key
     * @param string $defaultChannel Default Paloma channel
     * @param string $defaultLocale Default locale
     * @param SessionInterface|null $session
     * @param LoggerInterface|null $logger
     * @param string $successLogFormat
     * @param string $errorLogFormat
     * @param PalomaProfiler|null $profiler
     */
    public function __construct($baseUrl, $apiKey, $defaultChannel, $defaultLocale,
                                SessionInterface $session = null,
                                LoggerInterface $logger = null,
                                $successLogFormat = null,
                                $errorLogFormat = null,
                                PalomaProfiler $profiler = null)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;

        $this->defaultChannel = $defaultChannel;
        $this->defaultLocale = $defaultLocale;

        $this->session = $session;
        $this->logger = $logger;
        $this->successLogFormat = $successLogFormat;
        $this->errorLogFormat = $errorLogFormat;
        $this->profiler = $profiler;
    }

    /**
     * @param string|null $channel Paloma channel; if null default channel is used
     * @param string|null $locale Locale; if null, default locale is used
     * @return PalomaClient
     */
    public function create($channel = null, $locale = null)
    {
        $channel = $channel ?: $this->defaultChannel;
        $locale = $locale ?: $this->defaultLocale;

        $baseUrl = $this->baseUrl;
        if (!preg_match('/\/$/', $baseUrl)) {
            $baseUrl = $baseUrl . '/';
        }

        return new PalomaClient(
            new CatalogClient($baseUrl . 'catalog/v2/', $this->apiKey, $channel, $locale, $this->logger, $this->successLogFormat, $this->errorLogFormat, $this->profiler),
            new CheckoutClient($baseUrl . 'checkout/v2/', $this->apiKey, $channel, $locale, $this->session, $this->logger, $this->successLogFormat, $this->errorLogFormat, $this->profiler),
            new CustomersClient($baseUrl . 'customers/v2/', $this->apiKey, $channel, $locale, $this->session, $this->logger, $this->successLogFormat, $this->errorLogFormat, $this->profiler)
        );
    }

    /**
     * @param $locale
     * @return PalomaClient
     */
    public function forLocale($locale)
    {
        return $this->create(null, $locale);
    }

    /**
     * @param $channel
     * @return PalomaClient
     */
    public function forChannel($channel)
    {
        return $this->create($channel, null);
    }
}
