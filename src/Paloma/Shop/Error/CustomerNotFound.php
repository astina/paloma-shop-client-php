<?php

namespace Paloma\Shop\Error;

class CustomerNotFound extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 404;
    }
}