<?php

namespace Paloma\Shop\Checkout;

class CartModification implements CartModificationInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getCode(): string
    {
        return $this->data['code'];
    }

    function getItemId(): ?string
    {
        return $this->data['itemId'] ?? null;
    }

    function getParams(): array
    {
        return $this->data['params'] ?? [];
    }
}