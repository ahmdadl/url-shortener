<?php

namespace App\Shorteners;

use App\interfaces\ShortenerMethod;

class StringShortener implements ShortenerMethod
{
    public function shorten(string $url): string|int
    {
        $url = preg_replace("/[^A-Za-z]/", "", $url);
        $url = str_split($url);

        $url = array_unique($url);
        shuffle($url);

        $url = array_slice($url, 0, 5);
        $url = implode("", $url);

        $url .= substr($this->generateRandomString(), 0, 5);

        return str_shuffle($url);
    }

    public function generateRandomString($length = 5): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
