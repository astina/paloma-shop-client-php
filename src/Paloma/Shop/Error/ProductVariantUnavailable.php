<?php

namespace Paloma\Shop\Error;

class ProductVariantUnavailable extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 400;
    }
}