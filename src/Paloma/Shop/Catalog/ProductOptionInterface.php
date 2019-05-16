<?php

namespace Paloma\Shop\Catalog;

interface ProductOptionInterface
{
    /**
     * @return string Option code
     */
    function getOption(): string;

    /**
     * @return string Option label, to be displayed to the user (e.g. "Size")
     */
    function getLabel(): string;

    /**
     * @return ProductOptionValueInterface[] List of available values for this option (e.g. "S", "M", "L")
     */
    function getValues(): array;
}