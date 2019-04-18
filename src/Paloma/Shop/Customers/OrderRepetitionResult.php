<?php

namespace Paloma\Shop\Customers;

class OrderRepetitionResult implements OrderRepetitionResultInterface
{
    private $status;

    /**
     * @var OrderRepetitionResultItemInterface[]
     */
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;

        $allSuccessful = true;
        foreach ($this->items as $item) {
            if ($item->getStatus() !== OrderRepetitionResultItemInterface::STATUS_SUCCESS) {
                $allSuccessful = false;
                break;
            }
        }

        $status = OrderRepetitionResultInterface::STATUS_SUCCESS;
        if (!$allSuccessful || count($this->items) === 0) {
            $status = OrderRepetitionResultInterface::STATUS_FAILED;
        }

        $this->status = $status;
    }

    function getStatus(): string
    {
        return $this->status;
    }

    function getItems(): array
    {
        return $this->items;
    }
}