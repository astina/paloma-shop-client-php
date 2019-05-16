<?php

namespace Paloma\Shop\Error;

class OrderNotFound extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 404;
    }
}