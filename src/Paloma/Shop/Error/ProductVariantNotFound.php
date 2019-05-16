<?php

namespace Paloma\Shop\Error;

class ProductVariantNotFound extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 404;
    }
}