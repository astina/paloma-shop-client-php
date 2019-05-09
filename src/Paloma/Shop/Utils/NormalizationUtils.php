<?php

namespace Paloma\Shop\Utils;

class NormalizationUtils
{
    public static function copyKeys($keys, $data)
    {
        $copy = [];

        foreach ($keys as $key) {
            $copy[$key] = $data[$key];
        }

        return $copy;
    }
}