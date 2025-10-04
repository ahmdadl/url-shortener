<?php

namespace App\Shorteners;

use App\interfaces\ShortenerMethod;

class MixedShortener implements ShortenerMethod
{
    public function shorten(string $url): string|int
    {
        $url = preg_replace("/[^A-Za-z0-9]/", "", $url);
        $url = str_split($url);
        
        $url = array_unique($url);
        shuffle($url);

        $url = array_slice($url, 0, 5);
        $url = implode("", $url);

        $url .= bin2hex(random_bytes(3));

        return $url;
    }
}
