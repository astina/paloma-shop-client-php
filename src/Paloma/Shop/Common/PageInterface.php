<?php

namespace Paloma\Shop\Common;

interface PageInterface
{
    /**
     * @return array
     */
    function getContent(): array;

    /**
     * @return int Page size
     */
    function getSize(): int;

    /**
     * @return int Page number, starting from 0
     */
    function getNumber(): int;

    /**
     * @return int Total number of search results
     */
    function getTotalElements(): int;

    /**
     * @return int Total number of pages
     */
    function getTotalPages(): int;

    /**
     * @return bool True if this is the last page
     */
    function isLast(): bool;

    /**
     * @return bool True if this is the first page
     */
    function isFirst(): bool;

    /**
     * @return string Sort property
     */
    function getSort(): ?string;

    /**
     * @return bool If results are in descending order
     */
    function isOrderDesc(): bool;
}