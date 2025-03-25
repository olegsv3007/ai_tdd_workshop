<?php

namespace App\Application\Command\Handler;

use App\Application\Command\UpdateTaskCommand;
use App\Domain\Entity\Task;
use App\Domain\Entity\TaskTag;
use App\Domain\Enum\TaskPriority;
use App\Domain\Enum\TaskStatus;
use App\Domain\Enum\TaskType;
use App\Domain\Repository\TaskRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateTaskHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
    ) {
    }

    public function __invoke(UpdateTaskCommand $command): Task
    {
        $task = $this->taskRepository->findById($command->taskId);

        $task->setTitle($command->title);
        $task->setDescription($command->description ?? '');
        $task->setStatus(TaskStatus::from($command->status));
        $task->setType(TaskType::from($command->type));
        $task->setPriority(TaskPriority::from($command->priority));
        $task->setReporter($command->reporter);
        $task->setAssignee($command->assignee);
        $task->setEstimatedHours($command->estimatedHours);

        $task->clearTags();

        if (!empty($command->tags)) {
            foreach ($command->tags as $tagName) {
                $tag = new TaskTag($tagName);
                $task->addTag($tag);
            }
        }

        $this->taskRepository->save($task);

        return $task;
    }
}
