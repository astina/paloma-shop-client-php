<?php

namespace Paloma\Shop\Error;

class UnknownPaymentMethod extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 400;
    }
}