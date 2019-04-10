<?php

namespace Paloma\Shop\Catalog;

class ImageSource implements ImageSourceInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getSize(): string
    {
        return $this->data['size'];
    }

    function getUrl(): string
    {
        return $this->data['url'];
    }
}