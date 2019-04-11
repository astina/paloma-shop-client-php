<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\Catalog\Price;

class OrderAdjustment implements OrderAdjustmentInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
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
        return $this->data['description'];
    }

    function getPrice(): string
    {
        // TODO better
        return (new Price($this->data['pricing']))->getPrice();
    }
}