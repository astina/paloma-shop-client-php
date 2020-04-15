<?php

namespace Paloma\Shop\Customers;

interface CustomerPaymentInstrumentInterface
{
    /**
     * @return string Unique payment instrument ID
     */
    function getId(): string;

    /**
     * @return string E.g. "VISA", "Mastercard"
     */
    function getType(): string;

    function getMaskedCardNumber(): string;

    function getExpirationMonth(): int;

    function getExpirationYear(): int;

    function isExpired(): bool;
}