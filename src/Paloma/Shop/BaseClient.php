<?php

namespace Paloma\Shop;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Cache\CacheItemPoolInterface;
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

    public function __construct($baseUrl, $apiKey, $channel, $locale, LoggerInterface $logger = null,
        $successLogFormat = null, $errorLogFormat = null, PalomaProfiler $profiler = null, CacheItemPoolInterface $cache = null, $traceId = null)
    {
        $handlerStack = HandlerStack::create();

        if ($logger) {
            $formatterSuccess = new MessageFormatter($successLogFormat !== null ? $successLogFormat : MessageFormatter::SHORT);
            $formatterError = new MessageFormatter($errorLogFormat !== null ? $errorLogFormat : MessageFormatter::DEBUG);
            $handlerStack->push(
                Middleware::tap(null, function ($request, $options, ResponseInterface $response) use ($logger, $formatterSuccess, $formatterError) {
                    if ($response->getStatusCode() < 400) {
                        $logger->debug($formatterSuccess->format($request, $response));
                    } else {
                        $logger->error($formatterError->format($request, $response));
                    }
                })
            );
        }

        if ($profiler) {
            $handlerStack->push(
                Middleware::tap(function () use ($profiler) {
                    $profiler->startRequest();
                }, function ($request, $options, $response) use ($profiler) {
                    $response->then(function ($value) use ($profiler, $request) {
                        $profiler->endRequest($request, $value);
                    });
                })
            );
        }

        $headers = [ 'x-api-key' => $apiKey ];

        if ($traceId) {
            if (!preg_match('/^[a-z0-9]{8}$/', $traceId)) {
                throw new \Exception('Invalid trace ID: ' . $traceId);
            }
            $headers['x-astina-trace-id'] = $traceId;
            $headers['x-paloma-trace-id'] = $traceId;
        }

        $this->http = new Client([
            'base_uri' => $baseUrl,
            'headers' => $headers,
            'handler' => $handlerStack
        ]);

        $this->channel = $channel;
        $this->locale = $locale;
        $this->cache = $cache;
    }

    protected function get($path, $query = null, $useCache = false, $defaultCacheTtl = null)
    {
        return $this->req('GET', $path, $query, $useCache, $defaultCacheTtl);
    }

    protected function post($path, $query = null, $body = null, $useCache = false, $defaultCacheTtl = null)
    {
        return $this->req('POST', $path, $query, $body, $useCache, $defaultCacheTtl);
    }

    protected function postFormData($path, $query = null, $body = null, $useCache = false, $defaultCacheTtl = null)
    {
        return $this->req('POST', $path, $query, $body, true, $useCache, $defaultCacheTtl);
    }

    protected function put($path, $query = null, $body = null, $useCache = false, $defaultCacheTtl = null)
    {
        return $this->req('PUT', $path, $query, $body, $useCache, $defaultCacheTtl);
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

    private function cacheKeyForArray(array $arr, $base = true)
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

}
