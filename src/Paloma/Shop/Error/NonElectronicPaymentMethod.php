<?php

namespace Paloma\Shop\Error;

class NonElectronicPaymentMethod extends AbstractPalomaException
{
    function getHttpStatus(): int
    {
        return 400;
    }
}