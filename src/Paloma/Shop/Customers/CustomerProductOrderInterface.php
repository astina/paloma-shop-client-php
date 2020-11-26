<?php

namespace Paloma\Shop\Customers;

interface CustomerProductOrderInterface
{
    function getOrderNumber(): string;

    function getQuantity(): float;

    function getUnit(): string;

    function getValidFrom(): \DateTime;
}