<?php

namespace Paloma\Shop\Error;

use Exception;

class BackendUnavailable extends AbstractPalomaException
{
    public static function ofException(Exception $exception)
    {
        return new BackendUnavailable('Backend unavailable', null, $exception);
    }

    function getHttpStatus(): int
    {
        return 503;
    }
}