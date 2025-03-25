<?php

namespace App\Application\Command\Handler;

use App\Application\Command\CreateTaskCommand;
use App\Domain\Entity\Task;
use App\Domain\Entity\TaskTag;
use App\Domain\Enum\TaskPriority;
use App\Domain\Enum\TaskStatus;
use App\Domain\Enum\TaskType;
use App\Domain\Repository\TaskRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateTaskHandler
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(
        TaskRepositoryInterface $taskRepository,
    ) {
        $this->taskRepository = $taskRepository;
    }

    public function __invoke(CreateTaskCommand $command): Task
    {
        $task = new Task(
            $command->title,
            $command->description ?? '',
            TaskStatus::from($command->status),
            TaskType::from($command->type),
            TaskPriority::from($command->priority),
            $command->reporter,
            $command->assignee,
            $command->estimatedHours
        );

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
