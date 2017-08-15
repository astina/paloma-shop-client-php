<?php

namespace Paloma\Shop;

use GuzzleHttp\Client;

abstract class BaseClient
{
    private $http;

    public function __construct($baseUrl, $apiKey)
    {
        $this->http = new Client([
            'base_uri' => $baseUrl,
            'headers' => [
                'x-api-key' => $apiKey,
                'content-type' => 'application/json'
            ]
        ]);
    }

    protected function get($path, $query = null)
    {
        return $this->req('GET', $path);
    }

    protected function post($path, $query = null, $body = null)
    {
        return $this->req('POST', $path, $query, $body);
    }

    private function req($method, $path, $query = null, $body = null)
    {
        $res = $this->http->request($method, $path, [
            'query' => $query,
            'body' => $body ? json_encode($body) : null,
        ]);

        return json_decode($res->getBody(), true);
    }

}