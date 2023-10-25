<?php

namespace Paloma\Shop\Catalog;

use Paloma\Shop\Common\SelfNormalizing;
use Paloma\Shop\Utils\NormalizationUtils;

class ProductAttribute implements ProductAttributeInterface, SelfNormalizing
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getType(): string
    {
        return $this->data['type'];
    }

    function getLabel(): ?string
    {
        return $this->data['label'];
    }

    function getValue(): ?string
    {
        return $this->data['value'];
    }

    function getValues(): array
    {
        $values = [];

        if (!isset($this->data['values'])) {
            return $values;
        }

        for ($i = 0; $i < count($this->data['values']); $i++) {
            $value = $this->data['values'][$i];
            $code = $this->data['valueCodes'][$i] ?? null;
            $values[] = [
                'value' => $value,
                'code' => $code,
            ];
        }

        return $values;
    }

    public function _normalize(): array
    {
        $data = NormalizationUtils::copyKeys([
            'type',
            'label',
            'value'
        ], $this->data);

        $data['values'] = $this->getValues();

        return $data;
    }
}