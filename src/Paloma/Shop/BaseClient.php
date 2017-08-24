<?php

namespace Paloma\Shop;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

abstract class BaseClient
{
    private $http;

    public function __construct($baseUrl, $apiKey, LoggerInterface $logger = null, PalomaProfiler $profiler = null)
    {
        $handlerStack = HandlerStack::create();
        $handlerStack->push(
            Middleware::log(
                $logger ? $logger : new Logger('Paloma'),
                new MessageFormatter(($logger instanceof Logger && $logger->isHandling(Logger::DEBUG)) ? MessageFormatter::DEBUG : MessageFormatter::SHORT)
            )
        );

        if ($profiler) {
            $handlerStack->push(
                Middleware::tap(function() use ($profiler) {
                    $profiler->startRequest();
                }, function($request, $options, $response) use ($profiler) {
                    $response->then(function($value) use ($profiler, $request) {
                        $profiler->endRequest($request, $value);
                    });
                })
            );
        }

        $this->http = new Client([
            'base_uri' => $baseUrl,
            'headers' => [
                'x-api-key' => $apiKey
            ],
            'handler' => $handlerStack
        ]);
    }

    protected function get($path, $query = null)
    {
        return $this->req('GET', $path, $query);
    }

    protected function post($path, $query = null, $body = null)
    {
        return $this->req('POST', $path, $query, $body);
    }

    protected function postFormData($path, $query = null, $body = null)
    {
        return $this->req('POST', $path, $query, $body, true);
    }

    protected function put($path, $query = null, $body = null)
    {
        return $this->req('PUT', $path, $query, $body);
    }

    protected function delete($path, $query = null, $body = null)
    {
        return $this->req('DELETE', $path, $query, $body);
    }

    protected function patch($path, $query = null, $body = null)
    {
        return $this->req('PATCH', $path, $query, $body);
    }

    private function req($method, $path, $query = null, $body = null, $formEncoding = false)
    {
        $res = $this->http->request(
            $method,
            $path,
            [
                'headers' => [
                    'content-type' => $formEncoding ? 'application/x-www-form-urlencoded' : 'application/json'
                ],
                'query' => $query,
                'form_params' => $body && $formEncoding ? $body : null,
                'json' => $body && !$formEncoding ? $body : null
            ]);

        return json_decode($res->getBody(), true);
    }

}