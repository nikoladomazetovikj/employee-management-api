<?php

namespace App\Enums;

enum Role: int
{
    case MANAGER = 1;
    case EMPLOYEE = 2;

    public static function getDescription($value): string
    {
        return match ($value) {
            self::MANAGER->value => 'Manager',
            self::EMPLOYEE->value => 'Employee',
            default => '',
        };
    }
}
