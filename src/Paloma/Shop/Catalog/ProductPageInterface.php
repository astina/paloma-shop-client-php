<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\Common\PageInterface;

interface ProductPageInterface extends PageInterface
{
    /**
     * @return ProductInterface[]
     */
    function getContent(): array;

    /**
     * @return FilterAggregateInterface[]
     */
    function getFilterAggregates(): array;
}