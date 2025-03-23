<?php

namespace App\Domain\Enum;

enum TaskType: string
{
    case BUG = 'Bug';
    case FEATURE = 'Feature';
    case EPIC = 'Epic';
    case STORY = 'Story';
    case TASK = 'Task';
}
