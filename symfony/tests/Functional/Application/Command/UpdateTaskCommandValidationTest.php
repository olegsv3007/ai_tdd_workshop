<?php

namespace App\Tests\Functional\Application\Command;

use App\Application\Command\UpdateTaskCommand;
use App\Domain\Entity\Task;
use App\Domain\Enum\TaskPriority;
use App\Domain\Enum\TaskStatus;
use App\Domain\Enum\TaskType;
use App\Tests\Fixture\TaskFixture;
use App\Tests\Validation\ValidationTestCase;

class UpdateTaskCommandValidationTest extends ValidationTestCase
{
    public function testEmptyFieldsValidation(): void
    {
        $command = new UpdateTaskCommand();
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

    public function testStringTypeValidation(): void
    {
        $command = new UpdateTaskCommand();
        $command->title = [];
        $command->description = [];
        $command->status = [];
        $command->type = [];
        $command->priority = [];
        $command->reporter = [];
        $command->assignee = [];
        $command->tags = [new UpdateTaskCommand()];

        $errors = $this->validator->validate($command);

        self::assertFieldInvalid($errors, 'title', 'Title must be a string');
        self::assertFieldInvalid($errors, 'description', 'Description must be a string');
        self::assertFieldInvalid($errors, 'status', 'Status must be a string');
        self::assertFieldInvalid($errors, 'type', 'Type must be a string');
        self::assertFieldInvalid($errors, 'priority', 'Priority must be a string');
        self::assertFieldInvalid($errors, 'reporter', 'Reporter must be a string');
        self::assertFieldInvalid($errors, 'assignee', 'Assignee must be a string');
        self::assertFieldInvalid($errors, 'tags[0]', 'Tag must be a string');
    }

    public function testMinLengthValidation(): void
    {
        $command = new UpdateTaskCommand();
        $command->title = '';
        $command->reporter = '';

        $errors = $this->validator->validate($command);

        self::assertFieldInvalid($errors, 'title', 'Title must have at least 5 characters');
        self::assertFieldInvalid($errors, 'reporter', 'Reporter must have at least 3 characters');
    }

    public function testEnumFieldsValidation(): void
    {
        $command = new UpdateTaskCommand();

        $command->status = 'UnknownTaskStatus';
        $command->type = 'UnknownTaskType';
        $command->priority = 'UnknownTaskPriority';

        $errors = $this->validator->validate($command);

        self::assertFieldInvalid($errors, 'status', 'Invalid Status value');
        self::assertFieldInvalid($errors, 'type', 'Invalid Type value');
        self::assertFieldInvalid($errors, 'priority', 'Invalid Priority value');
    }

    public function testNumberTypeValidation(): void
    {
        $command = new UpdateTaskCommand();
        $command->taskId = 3.5;
        $command->estimatedHours = 3.5;

        $errors = $this->validator->validate($command);

        self::assertFieldInvalid($errors, 'taskId', 'Task Id must be a number');
        self::assertFieldInvalid($errors, 'estimatedHours', 'Estimated Hours must be a number');
    }

    public function testMinValuesValidation(): void
    {
        $command = new UpdateTaskCommand();
        $command->estimatedHours = -1;

        $errors = $this->validator->validate($command);

        self::assertFieldInvalid($errors, 'estimatedHours', 'Estimated Hours must be greater than 0');
    }

    public function testArrayValidation(): void
    {
        $command = new UpdateTaskCommand();
        $command->tags = new UpdateTaskCommand();

        $errors = $this->validator->validate($command);

        self::assertFieldInvalid($errors, 'tags', 'Tags must be array');
    }

    public function testTaskMustExist(): void
    {
        $command = new UpdateTaskCommand();
        $command->taskId = -1;

        $errors = $this->validator->validate($command);

        self::assertFieldInvalid($errors, 'taskId', 'Task not found');
    }

    public function testCommandWithPossibleValuesIsValid(): void
    {
        $existingTask = $this->loadTask();

        $command = new UpdateTaskCommand();
        $command->taskId = $existingTask->getId();
        $command->title = 'Task Title';
        $command->description = 'Task Description';
        $command->status = TaskStatus::TODO->value;
        $command->type = TaskType::BUG->value;
        $command->priority = TaskPriority::CRITICAL->value;
        $command->reporter = 'John Doe';
        $command->assignee = 'Mr Smith';
        $command->estimatedHours = 1;
        $command->tags = ['tag1', 'tag2', 'tag3'];

        $errors = $this->validator->validate($command);

        self::assertNoValidationErrors($errors);
    }

    private function loadTask(): Task
    {
        return $this->loadFixture(TaskFixture::class, Task::class);
    }
}
