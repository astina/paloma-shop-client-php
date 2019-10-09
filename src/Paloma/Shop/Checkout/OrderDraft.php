<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\Catalog\Price;
use Paloma\Shop\Common\MetadataContainingObject;
use Paloma\Shop\Utils\PriceUtils;

class OrderDraft implements OrderDraftInterface, MetadataContainingObject
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

    function getBilling(): OrderBillingInterface
    {
        return OrderBilling::ofCartData($this->data);
    }

    function getShipping(): OrderShippingInterface
    {
        return OrderShipping::ofCartData($this->data);
    }

    /**
     * @return bool Returns true if this payment method requires payment during the checkout process.
     */
    function isRequiresPaymentDuringCheckout(): bool
    {
        if ($this->data['orderPricing']['grossPrice'] == 0) {
            return false;
        }

        $paymentMethod = $this->getBilling()->getPaymentMethod();

        return $paymentMethod && $paymentMethod->getType() === 'electronic';
    }

    /**
     * @return bool True, if billing and shipping address are identical
     */
    function isSameShippingAndBillingAddress(): bool
    {
        /** @noinspection PhpNonStrictObjectEqualityInspection */
        return $this->getBilling()->getAddress() !=  null && $this->getBilling()->getAddress() == $this->getShipping()->getAddress();
    }

    /**
     * @return OrderItemDraftInterface[]
     */
    function getItems(): array
    {
        return array_map(function($elem) {
            return new OrderItemDraft($elem);
        }, $this->data['items']);
    }

    /**
     * @return OrderDraftModificationInterface[]
     */
    function getModifications(): array
    {
        return array_map(function($elem) {
            return new OrderDraftModification($elem);
        }, ($this->data['modifications'] ?? []));
    }

    function getItemsPrice(): string
    {
        return PriceUtils::format($this->data['itemsPricing']['currency'], $this->data['itemsPricing']['grossPriceFormatted']);
    }

    function getNetItemsPrice(): string
    {
        return PriceUtils::format($this->data['itemsPricing']['currency'], $this->data['itemsPricing']['netPriceFormatted']);
    }

    function getShippingPrice(): ?string
    {
        foreach (($this->data['adjustments'] ?? []) as $adj) {
            if ($adj['type'] === 'shipping') {
                return PriceUtils::format($adj['pricing']['currency'], $adj['pricing']['grossPriceFormatted']);
            }
        }

        return $this->_getFreeShippingPrice();
    }

    function getNetShippingPrice(): ?string
    {
        foreach (($this->data['adjustments'] ?? []) as $adj) {
            if ($adj['type'] === 'shipping') {
                return PriceUtils::format($adj['pricing']['currency'], $adj['pricing']['netPriceFormatted']);
            }
        }

        return $this->_getFreeShippingPrice();
    }

    private function _getFreeShippingPrice()
    {
        // HACK get currency and formatting from total price

        $currency = $this->data['orderPricing']['currency'];
        $amount = $this->data['orderPricing']['grossPriceFormatted'];

        return sprintf('%s %s',
            $currency,
            preg_replace('/^0+/', '0', // replace leading zeroes with one zero
                preg_replace('/[0-9]/', '0', $amount))); // replace all numbers with zero
    }

    /**
     * @return OrderAdjustmentInterface[]
     */
    function getReductions(): array
    {
        $adjustments = [];

        foreach (($this->data['adjustments'] ?? []) as $adj) {
            if ($adj['type'] === 'discount' || $adj['type'] === 'promotion') {
                $adjustments[] = new OrderAdjustment($adj);
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
                if ($adj['type'] === 'surcharge') {
                    $adjustments[] = new OrderAdjustment($adj);
                }
            }
        }

        return $adjustments;
    }

    function getNetTotalPrice(): string
    {
        return PriceUtils::format($this->data['orderPricing']['currency'], $this->data['orderPricing']['netPriceFormatted']);
    }

    function getTotalPrice(): string
    {
        return PriceUtils::format($this->data['orderPricing']['currency'], $this->data['orderPricing']['grossPriceFormatted']);
    }

    /**
     * @return OrderAdjustmentInterface[]
     */
    function getIncludedTaxes(): array
    {
        $adjustments = [];

        foreach (($this->data['taxSummary']['taxes'] ?? []) as $tax) {
            $adjustments[] = OrderAdjustment::ofTax($tax, $this->data['currency']);
        }

        return $adjustments;
    }

    /**
     * @return OrderCouponInterface[]
     */
    function getCoupons(): array
    {
        return array_map(function($elem) {
            return new OrderCoupon($elem);
        }, $this->data['coupons']);
    }

    function getMetaValidation()
    {
        if (!isset($this->data['_validation'])) {
            return null;
        }

        $validation = [];

        // Map shipping and billing address validations to our model

        foreach (['shipping', 'billing'] as $prop) {
            if (isset($this->data['_validation']['properties'][$prop. 'Address']['properties'])) {
                $address = $this->data['_validation']['properties'][$prop . 'Address']['properties'];
                $validation[$prop] = [
                    'address' => $address,
                ];
            }
        }

        return $validation;
    }
}