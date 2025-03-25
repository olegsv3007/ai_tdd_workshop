<?php

namespace App\Tests\Functional\Application\Command\Handler;

use App\Application\Command\UpdateTaskCommand;
use App\Domain\Entity\Task;
use App\Domain\Entity\TaskTag;
use App\Domain\Enum\TaskPriority;
use App\Domain\Enum\TaskStatus;
use App\Domain\Enum\TaskType;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Tests\Fixture\TaskFixture;
use App\Tests\Functional\FunctionalTestCase;

class UpdateTaskHandlerTest extends FunctionalTestCase
{
    private TaskRepositoryInterface $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskRepository = self::getService(TaskRepositoryInterface::class);
    }

    public function testCreateTaskHandler()
    {
        $existingTask = $this->loadTask();

        $command = new UpdateTaskCommand();

        $command->taskId = $existingTask->getId();
        $command->title = 'Test task';
        $command->description = 'Test description';
        $command->status = TaskStatus::IN_PROGRESS->value;
        $command->type = TaskType::FEATURE->value;
        $command->priority = TaskPriority::HIGH->value;
        $command->reporter = 'John Doe';
        $command->assignee = 'Mr Smith';
        $command->estimatedHours = 2;
        $command->tags = ['tag1', 'fixture', 'tag3'];

        self::getCommandBus()->dispatch($command);

        $actualTask = $this->taskRepository->findById($existingTask->getId());

        self::assertEquals($command->title, $actualTask->getTitle());
        self::assertEquals($command->description, $actualTask->getDescription());
        self::assertEquals(TaskStatus::from($command->status), $actualTask->getStatus());
        self::assertEquals(TaskType::from($command->type), $actualTask->getType());
        self::assertEquals(TaskPriority::from($command->priority), $actualTask->getPriority());
        self::assertEquals($command->reporter, $actualTask->getReporter());
        self::assertEquals($command->assignee, $actualTask->getAssignee());
        self::assertEquals($command->estimatedHours, $actualTask->getEstimatedHours());
        self::assertTaskHasTags($actualTask, $command->tags);
    }

    /**
     * @param array<int, string> $tags
     */
    private static function assertTaskHasTags(Task $task, array $tags): void
    {
        $existingTags = $task->getTags()->map(static fn (TaskTag $tag) => $tag->getName())->toArray();

        foreach ($tags as $tag) {
            self::assertContains($tag, $existingTags);
        }
    }

    private function loadTask(): Task
    {
        return $this->loadFixture(TaskFixture::class, Task::class);
    }
}
