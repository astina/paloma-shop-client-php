<?php

namespace Paloma\Shop\Error;

class BadCredentials extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 403;
    }
}