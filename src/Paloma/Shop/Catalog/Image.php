<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\Common\SelfNormalizing;

class Image implements ImageInterface, SelfNormalizing
{
    private $data;

    private $_sources = null; // cache

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getName(): string
    {
        return $this->data['name'];
    }

    function getSource(string $size): ?ImageSourceInterface
    {
        return $this->getSources()[$size] ?? null;
    }

    function getSources(): array
    {
        if ($this->_sources !== null) {
            return $this->_sources;
        }

        $sources = [];

        foreach ($this->data['sources'] ?? [] as $source) {
            $sources[$source['size']] = new ImageSource($source);
        }

        $this->_sources = $sources;

        return $sources;
    }

    public function _normalize(): array
    {
        $sources = [];
        foreach ($this->data['sources'] ?? [] as $source) {
            $sources[$source['size']] = [
                'size' => $source['size'],
                'url' => $source['url'],
            ];
        }

        return [
            'name' => $this->data['name'],
            'sources' => $sources,
        ];
    }
}