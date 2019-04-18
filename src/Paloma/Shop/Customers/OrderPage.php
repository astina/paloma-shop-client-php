<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\Common\Page;

class OrderPage extends Page implements OrderPageInterface
{
    /**
     * @return OrderInterface[]
     */
    function getContent(): array
    {
        return array_map(function ($elem) {
            return new Order($elem);
        }, $this->data['content']);
    }
}