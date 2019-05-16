<?php

namespace Paloma\Shop\Error;

class CartItemNotFound extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 404;
    }
}