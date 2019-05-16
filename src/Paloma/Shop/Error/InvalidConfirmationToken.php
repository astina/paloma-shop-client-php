<?php

namespace Paloma\Shop\Error;

class InvalidConfirmationToken extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 400;
    }
}