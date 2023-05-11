<?php

namespace Paloma\Shop;

use Paloma\Shop\Catalog\CatalogClient;
use Paloma\Shop\Checkout\CheckoutClient;
use Paloma\Shop\Customers\CustomersClient;

class PalomaClientFactory
{
    /**
     * @var array
     */
    private $options;

    /**
     * PalomaFactory accepts an array of constructor parameters:
     *
     * - base_url: (req) the Paloma API endpoint URL
     * - api_key: (req) the Paloma API key
     * - channel: (req) the Paloma channel
     * - locale: (req) the Paloma locale
     * - request_stack: (opt) a RequestStack implementation
     * - profiler: (opt) a PalomaProfiler instance
     * - logger: (opt) a LoggerInterface implementation
     * - log_format_success: (opt) a MessageFormatter format string
     * - log_format_failure: (opt) a MessageFormatter format string
     * - cache: (opt) a CacheItemPoolInterface implementation
     * - trace_id: (opt) the trace ID to send to Paloma
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (empty($options['base_url'])) {
            throw new \InvalidArgumentException('base_url is required');
        }
        // The rest of the parameters are checked in BaseClient.

        $this->options = $options;
    }

    /**
     * @param string|null $channel Paloma channel; if null default channel is used
     * @param string|null $locale Locale; if null, default locale is used
     * @param string|null $traceId
     * @return PalomaClient
     */
    public function create($channel = null, $locale = null, $traceId = null)
    {
        $params = $this->options;

        $params['channel'] = $channel ?: $this->options['channel'];
        $params['locale'] = $locale ?: $this->options['locale'];
        $params['trace_id'] = $traceId ?: $this->options['trace_id'];

        $baseUrl = $this->options['base_url'];
        if (!preg_match('/\/$/', $baseUrl)) {
            $baseUrl = $baseUrl . '/';
        }

        return new PalomaClient(
            new CatalogClient($baseUrl . 'catalog/v2/', $params),
            new CheckoutClient($baseUrl . 'checkout/v2/', $params),
            new CustomersClient($baseUrl . 'customers/v2/', $params)
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
