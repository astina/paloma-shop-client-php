<?php

namespace Paloma\Shop\Catalog;

interface ProductVariantOptionInterface
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
     * @return string Option value, to be displayed to the user (e.g. "XL")
     */
    function getValue(): string;
}