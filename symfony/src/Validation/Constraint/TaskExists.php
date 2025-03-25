<?php
namespace App\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class TaskExists extends Constraint
{
    public string $message = 'Task not found';
}
