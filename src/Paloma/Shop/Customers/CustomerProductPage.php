<?php

namespace Paloma\Shop\Customers;

use Paloma\Shop\Common\Page;

class CustomerProductPage extends Page implements CustomerProductPageInterface
{
    /**
     * @return CustomerProductInterface[]
     */
    function getContent(): array
    {
        return array_map(function ($elem) {
            return new CustomerProduct($elem);
        }, $this->data['content']);
    }
}