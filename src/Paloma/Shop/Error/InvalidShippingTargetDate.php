<?php

namespace Paloma\Shop\Error;

class InvalidShippingTargetDate extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 400;
    }
}