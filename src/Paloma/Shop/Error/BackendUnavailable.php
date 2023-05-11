<?php

namespace Paloma\Shop\Error;

use Exception;

class BackendUnavailable extends AbstractPalomaException
{
    public static function ofException(Exception $exception): BackendUnavailable
    {
        return new BackendUnavailable('Backend unavailable', 0, $exception);
    }

    function getHttpStatus(): int
    {
        return 503;
    }
}