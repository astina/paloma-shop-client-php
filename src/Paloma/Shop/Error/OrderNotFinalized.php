<?php

namespace Paloma\Shop\Error;

class OrderNotFinalized extends AbstractPalomaException
{
    public function __construct()
    {
        parent::__construct('Order not in status \'finalized\'');
    }

    function getHttpStatus(): int
    {
        return 400;
    }
}