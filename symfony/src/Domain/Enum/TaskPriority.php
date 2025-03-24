<?php

namespace App\Domain\Enum;

enum TaskPriority: string
{
    case LOW = 'Low';
    case MEDIUM = 'Medium';
    case HIGH = 'High';
    case CRITICAL = 'Critical';

    /**
     * @return string[] Array of enum values
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
