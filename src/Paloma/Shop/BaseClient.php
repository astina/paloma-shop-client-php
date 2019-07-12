<?php

namespace Paloma\Shop;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;

abstract class BaseClient
{
    private $http;

    protected $channel;

    protected $locale;

    /**
     * @var CacheItemPoolInterface
     */
    protected $cache = null;
    /**
     * @var bool
     */
    protected $useCache = false;
    /**
     * @var int
     */
    protected $defaultCacheTtl = null;

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
     * - use_cache: (opt) wheter to make of of a potentially specified cache
     * - default_cache_ttl: (opt) the default TTL for cache items
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
        $this->useCache = isset($options['use_cache']) ? $options['use_cache'] : null;
        $this->defaultCacheTtl = isset($options['default_cache_ttl']) ?
            $options['default_cache_ttl'] : null;
    }

    protected function get($path, $query = null)
    {
        return $this->req('GET', $path, $query, null, false);
    }

    protected function post($path, $query = null, $body = null, $bodyContentType = null)
    {
        return $this->req('POST', $path, $query, $body, false, $bodyContentType);
    }

    protected function postFormData($path, $query = null, $body = null)
    {
        return $this->req('POST', $path, $query, $body, true);
    }

    /**
     * @param array|null $parts An array of
     * [<part name> => ['contents' => <content stream>,
     *   'contentType' => <contents content-type>]]
     */
    protected function postMultipart($path, $query = null, array $parts = null)
    {
        $parts = $parts !== null ? $parts : [];
        $multipart = [];
        foreach ($parts as $name => $values) {
            $multipart[] = [
                'name' => $name,
                'contents' => $values['contents'],
                'headers' => ['Content-type' => $values['contentType']]
            ];
        }

        $res = $this->http->request(
            'POST',
            $path,
            [
                'headers' => ['content-type' => 'multipart/form-data'],
                'query' => $query,
                'multipart' => $multipart,
            ]);

        return $this->responseGetResult($res);
    }

    protected function put($path, $query = null, $body = null, $bodyContentType = null)
    {
        return $this->req('PUT', $path, $query, $body, false, $bodyContentType);
    }

    protected function delete($path, $query = null, $body = null, $bodyContentType = null)
    {
        return $this->req('DELETE', $path, $query, $body, $bodyContentType);
    }

    protected function patch($path, $query = null, $body = null, $bodyContentType = null)
    {
        return $this->req('PATCH', $path, $query, $body, $bodyContentType);
    }

    private function req($method, $path, $query = null, $body = null, $formEncoding = false, $bodyContentType = null)
    {
        $cacheItem = null;
        if ($this->cache !== null && $this->useCache) {
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
                    'content-type' => $bodyContentType !== null ? $bodyContentType :
                        ($formEncoding ? 'application/x-www-form-urlencoded' : 'application/json')
                ],
                'query' => $query,
                'form_params' => $body && $formEncoding ? $body : null,
                'body' => $body instanceof StreamInterface ? $body :
                    (is_array($body) && !$formEncoding ? json_encode($body, JSON_UNESCAPED_SLASHES) : null)
            ]);

        $result = $this->responseGetResult($res);

        if ($cacheItem !== null) {
            $cacheItem->set($result);
            if ($this->defaultCacheTtl !== null) {
                $cacheItem->expiresAfter($this->defaultCacheTtl);
            }
            $this->cache->save($cacheItem);
        }

        return $result;
    }

    private function responseGetResult(Psr7\Response $response)
    {
        $contentType = $response->hasHeader('Content-type') ? Psr7\parse_header(
            $response->getHeader('Content-type'))[0][0] : null;
        $hasContentDisposition = $response->hasHeader('Content-disposition');
        if ($contentType == 'application/json') {
            return json_decode($response->getBody(), true);
        } elseif ($hasContentDisposition) {
            return FileResponse::createFromResponse($response);
        } else {
            return $response->getBody();
        }
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
