<?php

namespace Paloma\Shop\Error;

use Exception;

abstract class AbstractPalomaException extends Exception implements PalomaException
{
    function getHttpStatus(): int
    {
        return 500;
    }
}