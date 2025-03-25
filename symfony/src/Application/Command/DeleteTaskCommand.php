<?php

namespace App\Application\Command;

use App\Validation\Constraint\TaskExists;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteTaskCommand
{
    #[Assert\NotBlank(message: 'Task ID is required')]
    #[TaskExists]
    public int $taskId;
}