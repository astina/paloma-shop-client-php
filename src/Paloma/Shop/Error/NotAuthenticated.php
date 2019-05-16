<?php

namespace Paloma\Shop\Error;

class NotAuthenticated extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 401;
    }
}