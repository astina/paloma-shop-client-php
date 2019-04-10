<?php

namespace Paloma\Shop\Catalog;

use DateTime;

interface ProductAvailabilityInterface
{
    /**
     * @return bool True if the product variant is currently available for purchase
     */
    function isAvailable(): bool;

    /**
     * @return DateTime Optional: availability date (if not yet available)
     */
    function getAvailableFrom(): DateTime;

    /**
     * @return int Optional: number of units currently available
     */
    function getAvailableStock(): int;
}