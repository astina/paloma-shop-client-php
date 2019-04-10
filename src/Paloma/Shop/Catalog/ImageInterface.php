<?php

namespace Paloma\Shop\Catalog;

interface ImageInterface
{
    /**
     * @return string Image name
     */
    function getName(): string;

    /**
     * @param string $size
     * @return ImageSourceInterface
     */
    function getSource($size = 'small'): ImageSourceInterface;
}