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
    function getSource(string $size): ImageSourceInterface;

    /**
     * @return ImageSourceInterface[]|array
     */
    function getSources(): array;
}