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

    /**
     * @return string Either 'product' or 'variant'
     */
    function getScope(): string;

    /**
     * @return string|null Variant SKU, if scope=variant
     */
    function getVariantSku(): ?string;
}