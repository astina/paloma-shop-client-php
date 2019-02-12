<?php

namespace Paloma\Shop;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class PalomaDataCollector extends DataCollector
{
    /** @var PalomaProfiler */
    private $profiler;

    public function __construct(PalomaProfiler $profiler)
    {
        $this->profiler = $profiler;
    }

    /**
     * Collects data for the given Request and Response.
     *
     * @param Request $request A Request instance
     * @param Response $response A Response instance
     * @param \Exception $exception An Exception instance
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'exchanges' => $this->profiler->getExchanges(),
            'time' => $this->profiler->getTotalTime(),
        ];
    }

    public function getExchanges()
    {
        return $this->data['exchanges'];
    }

    public function getTotalTime()
    {
        return round($this->data['time']);
    }

    /**
     * Returns the name of the collector.
     *
     * @return string The collector name
     */
    public function getName()
    {
        return 'paloma';
    }

    public function reset()
    {
        $this->data = [];
    }
}
