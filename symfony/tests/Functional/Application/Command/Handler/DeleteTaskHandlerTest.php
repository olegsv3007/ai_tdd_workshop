<?php

namespace App\Tests\Functional\Application\Command\Handler;

use App\Application\Command\DeleteTaskCommand;
use App\Domain\Entity\Task;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Tests\Fixture\TaskFixture;
use App\Tests\Fixture\TaskWithCustomTagsFixture;
use App\Tests\Functional\FunctionalTestCase;

class DeleteTaskHandlerTest extends FunctionalTestCase
{
    private TaskRepositoryInterface $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskRepository = self::getService(TaskRepositoryInterface::class);
    }

    public function testTaskDeleted(): void
    {
        $taskIdToDelete = $this->loadTask()->getId();
        $otherTask = $this->loadOtherTask();
        $command = new DeleteTaskCommand();
        $command->taskId = $taskIdToDelete;

        $this->getCommandBus()->dispatch($command);

        $existingTasks = $this->taskRepository->findAll();
        $removedTask = $this->taskRepository->findById($taskIdToDelete);

        self::assertCount(1, $existingTasks);
        self::assertNull($removedTask);
        self::assertEquals($otherTask->getId(), $existingTasks[0]->getId());
    }

    private function loadTask(): Task
    {
        return $this->loadFixture(TaskFixture::class, Task::class);
    }

    private function loadOtherTask(): Task
    {
        return $this->loadFixture(TaskWithCustomTagsFixture::class, Task::class);
    }
}