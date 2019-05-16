<?php

namespace Paloma\Shop\Error;

class OrderNotReadyForPayment extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 400;
    }
}