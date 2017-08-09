<?php

namespace Paloma\Shop\Catalog;

use GuzzleHttp\Client;

class CatalogClient implements CatalogClientInterface
{
    /**
     * @var Client
     */
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

    public function categories($country, $language)
    {
        return $this->get($country . '/' . $language . '/categories');
    }

    public function category($country, $language, $code)
    {
        return $this->get($country . '/' . $language . '/categories/' . $code);
    }

    public function categoryFilters($country, $language, $code)
    {
        return $this->get($country . '/' . $language . '/categories/' . $code . '/filter-aggregates');
    }

    public function product($country, $language, $itemNumber)
    {
        return $this->get($country . '/' . $language . '/products/' . $itemNumber);
    }

    function recommendedProducts($country, $language, $itemNumber)
    {
        return $this->get($country . '/' . $language . '/products/' . $itemNumber . '/recommended');
    }

    function similarProducts($country, $language, $itemNumber)
    {
        return $this->get($country . '/' . $language . '/products/' . $itemNumber . '/similar');
    }

    public function search($country, $language, $searchRequest)
    {
        return $this->post($country . '/' . $language . '/search', null, $searchRequest);
    }

    function searchSuggestions($country, $language, $partial)
    {
        return $this->get($country . '/' . $language . '/search/suggestions', ['query' => $partial]);
    }

    private function get($path, $query = null)
    {
        return $this->req('GET', $path);
    }

    private function post($path, $query = null, $body = null)
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