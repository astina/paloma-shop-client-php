<?php

namespace Paloma\Shop\Error;

class ProductNotFound extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 404;
    }
}