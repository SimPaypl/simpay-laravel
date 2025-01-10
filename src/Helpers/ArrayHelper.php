<?php

namespace SimPay\Laravel\Helpers;

class ArrayHelper
{
    public static function flatten(array $array): array
    {
        $return = [];

        array_walk_recursive($array, function ($a) use (&$return) {
            $return[] = $a;
        });

        return $return;
    }
}