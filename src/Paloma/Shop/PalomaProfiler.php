<?php

namespace Paloma\Shop;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PalomaProfiler
{
    private $exchanges = [];

    private $totalTime = 0;

    private $started = null;

    private $callStack = null;

    public function startRequest()
    {
        $this->callStack = (new \Exception())->getTraceAsString();
        $this->started = microtime(true);
    }

    public function endRequest(RequestInterface $request, ResponseInterface $response)
    {
        $duration = (microtime(true) - $this->started) * 1000;

        $this->totalTime += $duration;

        $responseBody = null;
        if (strpos($response->getHeaderLine('content-type'), 'application/json') === 0) {
            $responseBody = ($response->getBody() . '') ? json_encode(json_decode($response->getBody()), JSON_PRETTY_PRINT) : null;
        }

        $this->exchanges[] = [
            'request' => [
                'method' => $request->getMethod(),
                'url' => $request->getUri(),
                'body' => ($request->getBody() . '') ? json_encode(json_decode($request->getBody()), JSON_PRETTY_PRINT) : null,
            ],
            'response' => [
                'status' => sprintf('%d %s', $response->getStatusCode(), $response->getReasonPhrase()),
                'body' => $responseBody,
            ],
            'duration' => round($duration),
            'call_stack' => $this->callStack,
        ];
    }

    public function getExchanges()
    {
        return $this->exchanges;
    }

    public function getTotalTime()
    {
        return $this->totalTime;
    }
}
