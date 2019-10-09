<?php

namespace Paloma\Shop\Utils;

class PriceUtils
{
    /**
     * @param string $currency
     * @param string $amount Formatted amount
     * @return string Formatted currency with amount (e.g. "CHF 12.95")
     */
    public static function format(string $currency, string $amount)
    {
        return trim(sprintf('%s %s', $currency, $amount));
    }

    /**
     * Returns the formatted reduction from $price to $originalPrice (e.g. "-12 %")
     */
    public static function calculateReduction($price, $originalPrice): ?string
    {
        if (!$price || !$originalPrice) {
            return null;
        }

        $priceAmount = self::toMinorCurrencyAmount($price);
        $originalPriceAmount = self::toMinorCurrencyAmount($originalPrice);

        if ($priceAmount >= $originalPriceAmount || !$originalPriceAmount) {
            return null;
        }

        $reduction = round((($priceAmount / $originalPriceAmount) - 1) * 100);

        if (!$reduction) {
            return null;
        }

        return sprintf('%d %%', $reduction);
    }

    private static function toMinorCurrencyAmount($formattedAmount)
    {
        return (int) preg_replace('/[^0-9]/' , '', $formattedAmount);
    }
}