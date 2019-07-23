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
}