<?php

namespace Paloma\Shop;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;
use Monolog\Logger;

abstract class BaseClient
{
    private $http;

    private $apiKey;

    private $handlerStack;

    private $debug;

    public function __construct($baseUrl, $apiKey, $debug)
    {
        $this->http = new Client([
            'base_uri' => $baseUrl
        ]);
        $this->apiKey = $apiKey;

        $this->debug = $debug;
        if ($debug) {
            $this->handlerStack = HandlerStack::create();
            $this->handlerStack->push(
                Middleware::log(
                    new Logger('Logger'),
                    new MessageFormatter(MessageFormatter::DEBUG)
                )
            );
        }
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
        $res = $this->http->request($method, $path, [
            'headers' => [
                'x-api-key' => $this->apiKey,
                'content-type' => $formEncoding ? 'application/x-www-form-urlencoded' : 'application/json'
            ],
            'query' => $query,
            'form_params' => $body && $formEncoding ? $body : null,
            'json' => $body && !$formEncoding ? $body : null,
            'handler' => $this->debug ? $this->handlerStack : null
        ]);

        return json_decode($res->getBody(), true);
    }

}