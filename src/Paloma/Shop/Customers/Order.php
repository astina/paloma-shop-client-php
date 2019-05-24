<?php

namespace Paloma\Shop\Customers;

use DateTime;
use Paloma\Shop\Checkout\OrderAdjustmentInterface;
use Paloma\Shop\Checkout\OrderBilling;
use Paloma\Shop\Checkout\OrderBillingInterface;
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
        foreach (($this->data['adjustments'] ?? []) as $adj) {
            if ($adj['type'] === 'shipping') {
                return (new Price($this->data['currency'], $adj['grossItemTotal']))->getPrice();
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
                $adjustments[] = new OrderAdjustment($adj, $this->data['currency']);
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
                $adjustments[] = new OrderAdjustment($adj, $this->data['currency']);
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
    function getIncludedTaxes(): array
    {
        $adjustments = [];

        foreach (($this->data['totals']['includedTaxes'] ?? []) as $tax) {
            $adjustments[] = new OrderAdjustment([
                    'name' => sprintf('%s %s', $tax['rate'], $tax['name']),
                    'grossItemTotal' => $tax['amount'],
                ], $this->data['currency']);
        }

        return $adjustments;
    }

    function getModifications(): array
    {
        return [];
    }
}