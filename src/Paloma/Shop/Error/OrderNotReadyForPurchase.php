<?php

namespace Paloma\Shop\Error;

class OrderNotReadyForPurchase extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 400;
    }
}