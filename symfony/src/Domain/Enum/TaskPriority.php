<?php

namespace App\Domain\Enum;

enum TaskPriority: string
{
    case LOW = 'Low';
    case MEDIUM = 'Medium';
    case HIGH = 'High';
    case CRITICAL = 'Critical';
}
