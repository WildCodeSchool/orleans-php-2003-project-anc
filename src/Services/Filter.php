<?php


namespace App\Services;

class Filter
{
    public static function post($pattern, $input, $flags = 0)
    {
        return array_filter($input, function ($key) use ($pattern, $flags) {
            return preg_match($pattern, $key, $flags);
        }, ARRAY_FILTER_USE_KEY);
    }
}
