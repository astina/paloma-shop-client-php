<?php

namespace Paloma\Shop\Error;

class CustomerUserNotFound extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 404;
    }
}