<?php

namespace Paloma\Shop\Error;

class BackendUnavailable extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 503;
    }
}