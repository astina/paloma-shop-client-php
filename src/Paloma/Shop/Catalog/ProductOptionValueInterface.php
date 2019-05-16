<?php

namespace Paloma\Shop\Catalog;

interface ProductOptionValueInterface
{
    /**
     * @return string Option value
     */
    function getValue(): string;

    /**
     * @return string Option value label
     */
    function getLabel(): string;

    /**
     * @return array List of variant SKUs which have this option value assigned
     */
    function getVariants(): array;
}