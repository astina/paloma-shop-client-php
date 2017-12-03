<?php

namespace Paloma\Shop;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseClient
{
    private $http;

    protected $channel;

    protected $locale;

    /**
     * @var CacheItemPoolInterface
     */
    protected $cache;

    /**
     * BaseClient accepts an array of constructor parameters:
     *
     * - api_key: (req) the Paloma API key
     * - channel: (req) the Paloma channel
     * - locale: (req) the Paloma locale
     * - profiler: (opt) a PalomaProfiler instance
     * - logger: (opt) a LoggerInterface implementation
     * - log_format_success: (opt) a MessageFormatter format string
     * - log_format_failure: (opt) a MessageFormatter format string
     * - cache: (opt) a CacheItemPoolInterface implementation
     * - trace_id: (opt) the trace ID to send to Paloma
     *
     * @param string $baseUrl
     * @param array $options
     * @throws \Exception
     */
    public function __construct($baseUrl, array $options)
    {
        if (empty($options['api_key'])) {
            throw new \InvalidArgumentException('api_key is required');
        }
        if (empty($options['channel'])) {
            throw new \InvalidArgumentException('channel is required');
        }
        if (empty($options['locale'])) {
            throw new \InvalidArgumentException('locale is required');
        }

        $handlerStack = HandlerStack::create();

        if (!empty($options['profiler'])) {
            $handlerStack->push(self::profilerHandler($options['profiler']));
        }
        if (!empty($options['logger'])) {
            $handlerStack->push(self::logHandler($options['logger'],
                isset($options['log_format_success']) ? $options['log_format_success'] : null,
                isset($options['log_format_failure']) ? $options['log_format_failure'] : null));
        }

        $headers = [ 'x-api-key' => $options['api_key'] ];

        if (!empty($options['trace_id'])) {
            if (!preg_match('/^[a-z0-9]{8}$/', $options['trace_id'])) {
                throw new \Exception('Invalid trace ID: ' . $options['trace_id']);
            }
            $headers['x-astina-trace-id'] = $options['trace_id'];
            $headers['x-paloma-trace-id'] = $options['trace_id'];
        }

        $this->http = new Client([
            'base_uri' => $baseUrl,
            'headers' => $headers,
            'handler' => $handlerStack
        ]);

        $this->channel = $options['channel'];
        $this->locale = $options['locale'];
        $this->cache = isset($options['cache']) ? $options['cache'] : null;
    }

    protected function get($path, $query = null, $useCache = false, $defaultCacheTtl = null)
    {
        return $this->req('GET', $path, $query, null, false, $useCache, $defaultCacheTtl);
    }

    protected function post($path, $query = null, $body = null, $useCache = false, $defaultCacheTtl = null)
    {
        return $this->req('POST', $path, $query, $body, false, $useCache, $defaultCacheTtl);
    }

    protected function postFormData($path, $query = null, $body = null, $useCache = false, $defaultCacheTtl = null)
    {
        return $this->req('POST', $path, $query, $body, true, $useCache, $defaultCacheTtl);
    }

    protected function put($path, $query = null, $body = null, $useCache = false, $defaultCacheTtl = null)
    {
        return $this->req('PUT', $path, $query, $body, false, $useCache, $defaultCacheTtl);
    }

    protected function delete($path, $query = null, $body = null,
        $useCache = false, $defaultCacheTtl = null)
    {
        return $this->req('DELETE', $path, $query, $body, $useCache, $defaultCacheTtl);
    }

    protected function patch($path, $query = null, $body = null,
        $useCache = false, $defaultCacheTtl = null)
    {
        return $this->req('PATCH', $path, $query, $body, $useCache, $defaultCacheTtl);
    }

    private function req($method, $path, $query = null, $body = null, $formEncoding = false,
        $useCache = false, $defaultCacheTtl = null)
    {
        $cacheItem = null;
        if ($this->cache !== null && $useCache) {
            $cacheKey = md5($method . $path . $this->cacheKeyForArray($query) .
                $this->cacheKeyForArray($body));
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }
        }

        $res = $this->http->request(
            $method,
            $path,
            [
                'headers' => [
                    'content-type' => $formEncoding ? 'application/x-www-form-urlencoded' : 'application/json'
                ],
                'query' => $query,
                'form_params' => $body && $formEncoding ? $body : null,
                'body' => $body && !$formEncoding ? json_encode($body, JSON_UNESCAPED_SLASHES) : null
            ]);

        $data = json_decode($res->getBody(), true);

        if ($cacheItem !== null) {
            $cacheItem->set($data);
            if ($defaultCacheTtl !== null) {
                $cacheItem->expiresAfter($defaultCacheTtl);
            }
            $this->cache->save($cacheItem);
        }

        return $data;
    }

    private function cacheKeyForArray($arr, $base = true)
    {
        if ($arr === null) {
            return '';
        }
        foreach ($arr as $k => $v) {
            $arr[$k] = is_array($v) ? $this->cacheKeyForArray($v, false) :
                (string)$v;
        }
        ksort($arr);
        return $base ? json_encode($arr) : $arr;
    }

    private static function profilerHandler(PalomaProfiler $profiler)
    {
        return Middleware::tap(function () use ($profiler) {
            $profiler->startRequest();
        }, function (RequestInterface$request, $options, PromiseInterface $response) use ($profiler) {
            $response->then(function ($value) use ($profiler, $request) {
                $profiler->endRequest($request, $value);
            });
        });
    }

    private static function logHandler(LoggerInterface $logger, $logFormatSuccess = null, $logFormatFailure = null) {
        $formatterSuccess = new MessageFormatter($logFormatSuccess !== null ? $logFormatSuccess : MessageFormatter::SHORT);
        $formatterError = new MessageFormatter($logFormatFailure !== null ? $logFormatFailure : MessageFormatter::DEBUG);

        return Middleware::tap(null, function ($request, $options, PromiseInterface $response) use ($logger, $formatterSuccess, $formatterError) {
            $response->then(
                function (ResponseInterface $response) use ($logger, $request, $formatterSuccess, $formatterError) {
                    if ($response->getStatusCode() < 400) {
                        $logger->debug($formatterSuccess->format($request, $response));
                    } else {
                        $logger->error($formatterError->format($request, $response));
                    }
                },
                function ($reason) use ($logger, $request, $formatterError) {
                    $response = $reason instanceof RequestException ? $reason->getResponse() : null;
                    $logger->error($formatterError->format($request, $response, $reason));
                }
            );
        });
    }

}
