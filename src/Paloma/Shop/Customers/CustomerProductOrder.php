<?php

namespace Paloma\Shop\Customers;

class CustomerProductOrder implements CustomerProductOrderInterface
{
    private $data;

    function __construct(array $data)
    {
        $this->data = $data;
    }

    function getOrderNumber(): string
    {
        return $this->data['orderNumber'];
    }

    function getValidFrom(): \DateTime
    {
        return new \DateTime($this->data['validFrom']);
    }

    function getQuantity(): float
    {
        return $this->data['quantity'] ?? 1;
    }

    function getUnit(): string
    {
        return $this->data['unit'] ?? 'piece';
    }
}