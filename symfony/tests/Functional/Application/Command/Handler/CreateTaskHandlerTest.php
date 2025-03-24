<?php

namespace App\Tests\Functional\Application\Command\Handler;

use App\Application\Command\CreateTaskCommand;
use App\Domain\Entity\Task;
use App\Domain\Enum\TaskPriority;
use App\Domain\Enum\TaskStatus;
use App\Domain\Enum\TaskType;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Tests\Functional\FunctionalTestCase;

class CreateTaskHandlerTest extends FunctionalTestCase
{
    private TaskRepositoryInterface $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskRepository = self::getService(TaskRepositoryInterface::class);
    }

    public function testCreateTaskHandler()
    {
        $existingTasks = $this->taskRepository->findAll();
        self::assertEmpty($existingTasks);

        $command = new CreateTaskCommand();

        $command->title = 'Test task';
        $command->description = 'Test description';
        $command->status = TaskStatus::TODO->value;
        $command->type = TaskType::TASK->value;
        $command->priority = TaskPriority::MEDIUM->value;
        $command->reporter = 'John Doe';
        $command->assignee = 'Mr Smith';
        $command->estimatedHours = 2.5;
        $command->tags = ['tag1', 'tag2', 'tag3'];

        self::getCommandBus()->dispatch($command);

        $existingTasks = $this->taskRepository->findAll();

        self::assertCount(1, $existingTasks);
        self::assertEquals($command->title, $existingTasks[0]->getTitle());
        self::assertEquals($command->description, $existingTasks[0]->getDescription());
        self::assertEquals(TaskStatus::from($command->status), $existingTasks[0]->getStatus());
        self::assertEquals(TaskType::from($command->type), $existingTasks[0]->getType());
        self::assertEquals(TaskPriority::from($command->priority), $existingTasks[0]->getPriority());
        self::assertEquals($command->reporter, $existingTasks[0]->getReporter());
        self::assertEquals($command->assignee, $existingTasks[0]->getAssignee());
        self::assertEquals($command->estimatedHours, $existingTasks[0]->getEstimatedHours());
        self::assertTaskHasTags($existingTasks[0], $command->tags);
    }

    /**
     * @param array<int, string> $tags
     */
    private static function assertTaskHasTags(Task $task, array $tags): void
    {
        $existingTags = $task->getTags();

        foreach ($existingTags as $tag) {
            self::assertContains($tag->getName(), $tags);
        }
    }
}