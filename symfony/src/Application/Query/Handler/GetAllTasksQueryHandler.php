<?php

namespace App\Application\Query\Handler;

use App\Application\Query\GetAllTasksQuery;
use App\Domain\Entity\Task;
use App\Domain\Repository\TaskRepositoryInterface;

class GetAllTasksQueryHandler
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @return array<int, Task>
     */
    public function __invoke(GetAllTasksQuery $query): array
    {
        return $this->taskRepository->findAll();
    }
}
