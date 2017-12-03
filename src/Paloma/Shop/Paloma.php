<?php

namespace Paloma\Shop;

class Paloma
{
    public static function create(array $options)
    {
        $factory = new PalomaFactory($options);
        return $factory->create();
    }
}
