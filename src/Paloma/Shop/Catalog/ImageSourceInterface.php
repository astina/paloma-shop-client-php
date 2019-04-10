<?php

namespace Paloma\Shop\Catalog;

interface ImageSourceInterface
{
    /**
     * @return string Image size name
     */
    function getSize(): string;

    /**
     * @return string Image URL
     */
    function getUrl(): string;
}