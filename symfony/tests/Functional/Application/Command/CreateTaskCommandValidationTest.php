<?php

namespace App\Tests\Functional\Application\Command;

use App\Application\Command\CreateTaskCommand;
use App\Domain\Enum\TaskPriority;
use App\Domain\Enum\TaskStatus;
use App\Domain\Enum\TaskType;
use App\Tests\Validation\ValidationTestCase;

class CreateTaskCommandValidationTest extends ValidationTestCase
{
    public function testRequiredFieldsValidation(): void
    {
        $command = new CreateTaskCommand();

        $errors = $this->validator->validate($command);

        self::assertFieldInvalid($errors, 'title', 'Title is required');
        self::assertFieldInvalid($errors, 'description', 'Description is required');
        self::assertFieldInvalid($errors, 'status', 'Status is required');
        self::assertFieldInvalid($errors, 'type', 'Type is required');
        self::assertFieldInvalid($errors, 'priority', 'Priority is required');
        self::assertFieldInvalid($errors, 'reporter', 'Reporter is required');
        self::assertFieldInvalid($errors, 'assignee', 'Assignee is required');
        self::assertFieldInvalid($errors, 'estimated_hours', 'Estimated Hours is required');
    }

    public function testEmptyFieldsValidation(): void
    {
        $command = new CreateTaskCommand();
        $command->title = '';
        $command->status = '';
        $command->type = '';
        $command->priority = '';
        $command->reporter = '';

        $errors = $this->validator->validate($command);

        self::assertFieldInvalid($errors, 'title', 'Title can\'t be empty');
        self::assertFieldInvalid($errors, 'status', 'Status can\'t be empty');
        self::assertFieldInvalid($errors, 'type', 'Type can\'t be empty');
        self::assertFieldInvalid($errors, 'priority', 'Priority can\'t be empty');
        self::assertFieldInvalid($errors, 'reporter', 'Reporter can\'t be empty');
    }

    public function testMinLengthValidation(): void
    {
        $command = new CreateTaskCommand();
        $command->title = '';
        $command->reporter = '';

        $errors = $this->validator->validate($command);

        self::assertFieldInvalid($errors, 'title', 'Title must have at least 5 characters');
        self::assertFieldInvalid($errors, 'reporter', 'Reporter must have at least 3 characters');
    }

    public function testEnumFieldsValidation(): void
    {
        $command = new CreateTaskCommand();

        $command->status = 'UnknownTaskStatus';
        $command->type = 'UnknownTaskType';
        $command->priority = 'UnknownTaskPriority';

        $errors = $this->validator->validate($command);

        self::assertFieldInvalid($errors, 'status', 'Invalid Status value');
        self::assertFieldInvalid($errors, 'type', 'Invalid Type value');
        self::assertFieldInvalid($errors, 'priority', 'Invalid Priority value');
    }

    public function testMinValuesValidation(): void
    {
        $command = new CreateTaskCommand();
        $command->estimatedHours = -1;

        $errors = $this->validator->validate($command);

        self::assertFieldInvalid($errors, 'estimated_hours', 'Estimated Hours must be greater than 0');
    }

    public function testCommandWithPossibleValuesIsValid(): void
    {
        $command = new CreateTaskCommand();
        $command->title = 'Task Title';
        $command->description = 'Task Description';
        $command->status = TaskStatus::TODO->value;
        $command->type = TaskType::BUG->value;
        $command->priority = TaskPriority::CRITICAL->value;
        $command->reporter = 'John Doe';
        $command->assignee = 'Mr Smith';
        $command->estimatedHours = 1;

        $errors = $this->validator->validate($command);

        self::assertNoValidationErrors($errors);
    }
}