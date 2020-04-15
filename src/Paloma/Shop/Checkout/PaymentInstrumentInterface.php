<?php

namespace Paloma\Shop\Checkout;

use Paloma\Shop\Customers\CustomerPaymentInstrumentInterface;

interface PaymentInstrumentInterface extends CustomerPaymentInstrumentInterface
{
    /**
     * @return bool True, if this payment instrument is set for the current order
     */
    function isSelected(): bool;
}