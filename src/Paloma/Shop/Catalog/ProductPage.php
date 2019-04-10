<?php

namespace Paloma\Shop\Catalog;

class ProductPage implements ProductPageInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function createEmpty()
    {
        return new ProductPage([
            'content' => [],
            'size' => 0,
            'number' => 0,
            'totalElements' => 0,
            'totalPages' => 0,
            'last' => true,
            'first' => true,
            'sort' => null,
            'order' => 'asc',
        ]);
    }

    function getContent(): array
    {
        return array_map(function ($elem) {
            return new Product($elem);
        }, $this->data['content']);
    }

    function getFilterAggregates(): array
    {
        return isset($this->data['filterAggregates'])
            ? array_map(function ($elem) {
                return new FilterAggregate($elem);
            }, $this->data['filterAggregates'])
            : null;
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

    function getSort(): string
    {
        return $this->data['sort'];
    }

    function isOrderDesc(): bool
    {
        return $this->data['order'] === 'desc';
    }
}