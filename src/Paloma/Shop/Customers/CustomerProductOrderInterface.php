<?php

namespace Paloma\Shop\Customers;

interface CustomerProductOrderInterface
{
    function getOrderNumber(): string;

    function getValidFrom(): \DateTime;
}