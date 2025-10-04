<?php

namespace App\interfaces;

interface ShortenerMethod
{
    public function shorten(string $url): string|int;
}
