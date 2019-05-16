<?php

namespace Paloma\Shop\Error;

class CategoryNotFound extends AbstractPalomaException
{
    public function __construct()
    {
        parent::__construct('Category not found');
    }

    function getHttpStatus(): int
    {
        return 404;
    }
}