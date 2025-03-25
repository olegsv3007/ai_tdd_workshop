<?php

namespace App\Application\Command\Handler;

use App\Application\Command\DeleteTaskCommand;
use App\Domain\Repository\TaskRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DeleteTaskHandler
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function __invoke(DeleteTaskCommand $command): void
    {
        $task = $this->taskRepository->findById($command->taskId);

        if ($task) {
            $this->taskRepository->remove($task);
        }
    }
}