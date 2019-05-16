<?php

namespace Paloma\Shop\Error;

class CartIsEmpty extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 400;
    }
}