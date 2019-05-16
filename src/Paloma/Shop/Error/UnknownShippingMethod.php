<?php

namespace Paloma\Shop\Error;

class UnknownShippingMethod extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 400;
    }
}