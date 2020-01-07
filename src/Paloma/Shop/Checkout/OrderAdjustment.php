<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\Utils\PriceUtils;

class OrderAdjustment implements OrderAdjustmentInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function of(array $data, $currency)
    {
        return new OrderAdjustment([
            'description' => $data['name'],
            // TODO better
            'pricing' => [
                'currency' => $currency,
                'grossPriceFormatted' => $data['grossItemTotal'] ?? $data['linePrice'],
                'netPriceFormatted' => $data['netItemTotal'] ?? null,
            ]
        ]);
    }

    public static function ofTax($tax, $currency): OrderAdjustment
    {
        return new OrderAdjustment([
            'description' => sprintf('%s %s', $tax['rateFormatted'], $tax['name']),
            // TODO better
            'pricing' => [
                'currency' => $currency,
                'grossPriceFormatted' => $tax['amountFormatted'],
                'netPriceFormatted' => $tax['amountFormatted'],
            ]
        ]);
    }

    function getDescription(): string
    {
        return $this->data['description'] ?? $this->data['type'];
    }

    function getPrice(): string
    {
        return PriceUtils::format($this->data['pricing']['currency'], $this->data['pricing']['grossPriceFormatted']);
    }
}