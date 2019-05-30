<?php

namespace Paloma\Shop\Utils;

class ArrayUtils
{
    public static function isList($array)
    {
        $valueKeys = array_keys($array);

        // Assume list for empty array
        if (count($valueKeys) === 0) {
            return true;
        }

        // Assume list if first key is 0
        if ($valueKeys[0] === 0) {
            return true;
        }

        return false;
    }

    public static function allNull($array)
    {
        if ($array === null) {
            return true;
        }

        foreach ($array as $elem) {
            if ($elem !== null) {
                return false;
            }
        }

        return true;
    }
}