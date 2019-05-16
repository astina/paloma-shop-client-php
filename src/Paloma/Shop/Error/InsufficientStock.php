<?php

namespace Paloma\Shop\Error;

class InsufficientStock extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 400;
    }
}