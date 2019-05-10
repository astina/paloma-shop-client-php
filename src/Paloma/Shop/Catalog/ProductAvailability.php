<?php

namespace Paloma\Shop\Catalog;

use DateTime;
use Paloma\Shop\Common\SelfNormalizing;
use Paloma\Shop\Utils\NormalizationUtils;

class ProductAvailability implements ProductAvailabilityInterface, SelfNormalizing
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function isAvailable(): bool
    {
        return (bool) $this->data['available'];
    }

    function getAvailableFrom(): ?DateTime
    {
        return new DateTime($this->data['availableFrom']);
    }

    function getAvailableStock(): int
    {
        return (int) $this->data['availableStock'];
    }

    public function _normalize(): array
    {
        return NormalizationUtils::copyKeys([
            'available',
            'availableFrom',
            'availableStock'
        ], $this->data);
    }
}