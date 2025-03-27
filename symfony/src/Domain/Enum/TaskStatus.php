<?php

namespace App\Domain\Enum;

enum TaskStatus: string
{
    case TODO = 'To Do';
    case IN_PROGRESS = 'In Progress';
    case REVIEW = 'Review';
    case DONE = 'Done';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
