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
    function getSource(string $size): ?ImageSourceInterface;

    /**
     * @return ImageSourceInterface[] Map of image sources (using size as key)
     */
    function getSources(): array;
}