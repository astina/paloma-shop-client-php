<?php

namespace Paloma\Shop\Catalog;

interface FilterValueInterface
{
    /**
     * @return string Filter value
     */
    function getValue(): string;

    /**
     * @return int Number of occurrences for this filter value
     */
    function getCount(): int;
}