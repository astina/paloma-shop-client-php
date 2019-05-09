<?php

namespace Paloma\Shop\Catalog;

class Image implements ImageInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getName(): string
    {
        return $this->data['name'];
    }

    function getSource(string $size): ImageSourceInterface
    {
        foreach ($this->data['sources'] as $source) {
            if ($source['size'] === $size) {
                return new ImageSource($source);
            }
        }
    }

    function getSources(): array
    {
        return array_map(function($elem) {
            return new ImageSource($elem);
        }, $this->data['sources'] ?? []);
    }
}