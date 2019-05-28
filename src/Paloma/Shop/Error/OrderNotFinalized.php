<?php

namespace Paloma\Shop\Error;

class OrderNotFinalized extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 400;
    }
}