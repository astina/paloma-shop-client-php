<?php

namespace Paloma\Shop\Common;

use Paloma\Shop\Utils\NormalizationUtils;

abstract class Page implements PageInterface
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getSize(): int
    {
        return (int)$this->data['size'];
    }

    function getNumber(): int
    {
        return (int)$this->data['number'];
    }

    function getTotalElements(): int
    {
        return (int)$this->data['totalElements'];
    }

    function getTotalPages(): int
    {
        return (int)$this->data['totalPages'];
    }

    function isLast(): bool
    {
        return (bool)$this->data['last'];
    }

    function isFirst(): bool
    {
        return (bool)$this->data['first'];
    }

    function getSort(): ?string
    {
        return (!isset($this->data['sort']) || count($this->data['sort']) === 0)
            ? null
            : $this->data['sort'][0]['property'];
    }

    function isOrderDesc(): bool
    {
        return isset($this->data['order'])
            ? $this->data['order'][0]['direction'] === 'DESC'
            : false;
    }

    protected function _normalize()
    {
        $data = NormalizationUtils::copyKeys([
            'size',
            'number',
            'totalElements',
            'totalPages',
            'last',
            'first',
        ], $this->data);

        $data['sort'] = $this->getSort();
        $data['orderDesc'] = $this->isOrderDesc();

        return $data;
    }
}