<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case GUEST = 'guest';

    /**
     * get all values
     */
    public function values(): array
    {
        return array_values(self::cases());
    }
}
