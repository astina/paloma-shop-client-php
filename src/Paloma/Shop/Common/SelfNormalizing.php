<?php

namespace Paloma\Shop\Common;

/**
 * If an object is able to normalize itself (create an array-representation of itself)
 * it can implement this interface.
 */
interface SelfNormalizing
{
    public function _normalize(): array;
}