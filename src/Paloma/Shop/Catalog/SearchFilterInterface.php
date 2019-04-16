<?php

namespace Paloma\Shop\Catalog;

interface SearchFilterInterface
{
    /**
     * @see FilterAggregateInterface
     * @return string Filter name
     */
    function getName(): string;

    /**
     * @return array One or several filter values, will be applied using OR
     */
    function getValues(): array;

    /**
     * @return float Greater-than or equal to. Only applicable for numeric filter values.
     */
    function getGreaterThan(): ?float;

    /**
     * @return float Less-than or equal to. Only applicable for numeric filter values.
     */
    function getLessThan(): ?float;
}