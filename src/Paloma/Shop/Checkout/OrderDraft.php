<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\Catalog\Price;

class OrderDraft implements OrderDraftInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getBilling(): OrderBillingInterface
    {
        return new OrderBilling($this->data['billing']);
    }

    function getShipping(): OrderShippingInterface
    {
        return new OrderShipping($this->data['shipping']);
    }

    function getItems(): array
    {
        return array_map(function($elem) {
            return new OrderItemDraft($elem);
        }, $this->data['items']);
    }

    function getItemsPrice(): string
    {
        return (new Price($this->data['itemsPricing']))->getPrice();
    }

    function getShippingPrice(): ?string
    {
        foreach (($this->data['adjustments'] ?? []) as $adj) {
            if ($adj['type'] === 'shipping') {
                return (new Price($adj['pricing']))->getPrice();
            }
        }

        return null;
    }

    function getReductions(): array
    {
        $adjustments = [];

        foreach (($this->data['adjustments'] ?? []) as $adj) {
            if ($adj['type'] === 'discount') {
                $adjustments[] = new OrderAdjustment($adj);
            }
        }

        return $adjustments;
    }

    function getTaxes(): array
    {
        $adjustments = [];

        foreach (($this->data['adjustments'] ?? []) as $adj) {
            if ($adj['type'] === 'tax') {
                $adjustments[] = new OrderAdjustment($adj);
            }
        }

        return $adjustments;
    }

    function getTotalPrice(): string
    {
        return (new Price($this->data['orderPricing']))->getPrice();
    }

    function getIncludedTaxes(): array
    {
        $adjustments = [];

        foreach (($this->data['taxSummary']['taxes'] ?? []) as $tax) {
            $adjustments[] = OrderAdjustment::ofTax($tax, $this->data['currency']);
        }

        return $adjustments;
    }
}