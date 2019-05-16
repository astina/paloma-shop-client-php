<?php

namespace Paloma\Shop\Error;

interface PalomaException
{
    function getHttpStatus(): int;
}