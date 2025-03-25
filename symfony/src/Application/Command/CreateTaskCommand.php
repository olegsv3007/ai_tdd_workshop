<?php

namespace App\Application\Command;

use App\Domain\Enum\TaskPriority;
use App\Domain\Enum\TaskStatus;
use App\Domain\Enum\TaskType;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTaskCommand
{
    #[Assert\NotBlank(message: "Title can't be empty")]
    #[Assert\Type(type: "string", message: "Title must be a string")]
    #[Assert\Length(min: 5, minMessage: "Title must have at least 5 characters")]
    public $title;

    #[Assert\Type(type: "string", message: "Description must be a string")]
    public $description = null;

    #[Assert\NotBlank(message: "Status can't be empty")]
    #[Assert\Type(type: "string", message: "Status must be a string")]
    #[Assert\Choice(callback: [TaskStatus::class, 'getValues'], message: "Invalid Status value")]
    public $status;

    #[Assert\NotBlank(message: "Type can't be empty")]
    #[Assert\Type(type: "string", message: "Type must be a string")]
    #[Assert\Choice(callback: [TaskType::class, 'getValues'], message: "Invalid Type value")]
    public $type;

    #[Assert\NotBlank(message: "Priority can't be empty")]
    #[Assert\Type(type: "string", message: "Priority must be a string")]
    #[Assert\Choice(callback: [TaskPriority::class, 'getValues'], message: "Invalid Priority value")]
    public $priority;

    #[Assert\NotBlank(message: "Reporter can't be empty")]
    #[Assert\Type(type: "string", message: "Reporter must be a string")]
    #[Assert\Length(min: 3, minMessage: "Reporter must have at least 3 characters")]
    public $reporter;

    #[Assert\Type(type: "string", message: "Assignee must be a string")]
    public $assignee = null;

    #[Assert\Type(type: "integer", message: "Estimated Hours must be a number")]
    #[Assert\GreaterThan(value: 0, message: "Estimated Hours must be greater than 0")]
    public $estimatedHours = null;

    #[Assert\Type(type: "array", message: "Tags must be array")]
    #[Assert\All([
        new Assert\Type(type: "string", message: "Tag must be a string")
    ])]
    public $tags = null;
}
