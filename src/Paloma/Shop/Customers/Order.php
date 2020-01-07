<?php

namespace Paloma\Shop\Customers;

use DateTime;
use Paloma\Shop\Checkout\OrderAdjustment;
use Paloma\Shop\Checkout\OrderAdjustmentInterface;
use Paloma\Shop\Checkout\OrderBilling;
use Paloma\Shop\Checkout\OrderBillingInterface;
use Paloma\Shop\Checkout\OrderCouponInterface;
use Paloma\Shop\Checkout\OrderCustomer;
use Paloma\Shop\Checkout\OrderCustomerInterface;
use Paloma\Shop\Checkout\OrderShipping;
use Paloma\Shop\Checkout\OrderShippingInterface;
use Paloma\Shop\Common\Price;

class Order implements OrderInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getCustomer(): ?OrderCustomerInterface
    {
        if ($this->data['customer'] === null) {
            return null;
        }

        return new OrderCustomer($this->data['customer'], $this->data['locale']);
    }

    function getOrderNumber(): string
    {
        return $this->data['orderNumber'];
    }

    function getStatus(): string
    {
        return $this->data['status'];
    }

    function getOrderDate(): DateTime
    {
        return new DateTime($this->data['orderDate']);
    }

    function getBilling(): OrderBillingInterface
    {
        return new OrderBilling($this->data['billing']);
    }

    function getShipping(): OrderShippingInterface
    {
        return new OrderShipping($this->data['shipping']);
    }

    /**
     * @return OrderItemInterface[]
     */
    function getItems(): array
    {
        return array_map(function($elem) {
            return new OrderItem($elem, $this->data['currency']);
        }, $this->data['items']);
    }

    function getItemsPrice(): string
    {
        return (new Price($this->data['currency'], $this->data['totals']['itemsTotal']))->getPrice();
    }

    function getShippingPrice(): ?string
    {
        $adj = $this->getShippingAdjustment();
        if ($adj) {
            return (new Price($this->data['currency'], $adj['grossItemTotal']))->getPrice();
        }

        return null;
    }

    private function getShippingAdjustment()
    {
        foreach (($this->data['adjustments'] ?? []) as $adj) {
            if ($adj['type'] === 'shipping') {
                return $adj;
            }
        }

        return null;
    }

    /**
     * @return OrderAdjustmentInterface[]
     */
    function getReductions(): array
    {
        $adjustments = [];

        foreach (($this->data['adjustments'] ?? []) as $adj) {
            if ($adj['type'] === 'discount' || $adj['type'] === 'promotion') {
                $adjustments[] = OrderAdjustment::of($adj, $this->data['currency']);
            }
        }

        return $adjustments;
    }

    /**
     * @return OrderAdjustmentInterface[]
     */
    function getSurcharges(): array
    {
        $adjustments = [];

        foreach (($this->data['adjustments'] ?? []) as $adj) {
            if ($adj['type'] === 'tax' || $adj['type'] === 'surcharge') {
                $adjustments[] = OrderAdjustment::of($adj, $this->data['currency']);
            }
        }

        return $adjustments;
    }

    function getTotalPrice(): string
    {
        return (new Price($this->data['currency'], $this->data['totals']['orderTotal']))->getPrice();
    }

    /**
     * @return OrderAdjustmentInterface[]
     */
    function getTaxes(): array
    {
        $adjustments = [];

        $taxes = $this->data['totals']['taxes'] ?? $this->data['totals']['includedTaxes'];

        foreach ($taxes as $tax) {
            $adjustments[] = OrderAdjustment::of([
                    'name' => sprintf('%s %s', $tax['rate'], $tax['name']),
                    'linePrice' => $tax['amount'],
                ], $this->data['currency']);
        }

        return $adjustments;
    }

    /**
     * @deprecated
     */
    public function getIncludedTaxes()
    {
        return $this->getTaxes();
    }

    function isTaxIncluded(): bool
    {
        return $this->data['taxIncluded'] ?? true;
    }

    function getModifications(): array
    {
        return [];
    }

    /**
     * @return OrderCouponInterface[]
     */
    function getCoupons(): array
    {
        // TODO get from adjustments?

        return [];
    }
}