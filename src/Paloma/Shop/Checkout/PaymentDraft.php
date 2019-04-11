<?php

namespace Paloma\Shop\Checkout;

class PaymentDraft implements PaymentDraftInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getReference(): string
    {
        return $this->data['reference'];
    }

    function getCurrency(): string
    {
        return $this->data['currency'];
    }

    function getAmount(): string
    {
        return (string) $this->data['amount'];
    }

    function getProviderParams(): array
    {
        $providerRequest = ($this->data['providerRequest'] ?? []);

        $params = [];

        foreach ($providerRequest as $param) {
            $params[$param['name']] = $param['value'];
        }

        return $params;
    }
}