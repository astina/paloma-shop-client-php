<?php

namespace Paloma\Shop\Catalog;

interface FilterAggregateInterface
{
    /**
     * @return string Internal name for the filter
     */
    function getName(): string;

    /**
     * @return string Localized filter label
     */
    function getLabel(): string;

    /**
     * @return string One of "text" "bool" "number" "currency"
     */
    function getType(): string;

    /**
     * @return FilterValueInterface[]
     */
    function getValues(): array;

    /**
     * @return float Optional: minimum value (for numeric properties)
     */
    function getMin(): float;

    /**
     * @return float Optional: maximum value (for numeric properties)
     */
    function getMax(): float;
}