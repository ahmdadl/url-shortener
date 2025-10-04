<?php

namespace App\Enums\Enums;

enum ShortenerMethodEnum: string
{
    case STRING = 'string';
    case NUMBER = 'number';
    case MIXED = 'mixed';

    public static function default(): self
    {
        return self::STRING;
    }
}
