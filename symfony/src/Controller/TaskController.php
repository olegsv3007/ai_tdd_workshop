<?php

namespace App\Controller;

use App\Application\Bus\CommandBusContract;
use App\Application\Bus\QueryBusContract;
use App\Application\Command\CreateTaskCommand;
use App\Application\Query\GetAllTasksQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    public function __construct(
        private readonly QueryBusContract $queryBus,
    ) {
    }

    #[Route('/api/tasks', name: 'api_get_all_tasks', methods: ['GET'])]
    public function getAllTasks(): JsonResponse
    {
        $tasks = $this->queryBus->query(new GetAllTasksQuery());

        return $this->json($tasks, Response::HTTP_OK, [], ['groups' => 'task:read']);
    }

    #[Route('/api/tasks', name: 'task_create', methods: ['POST'])]
    public function createTask(
        #[MapRequestPayload] CreateTaskCommand $command,
        CommandBusContract $commandBus,
    ): JsonResponse
    {
        $commandBus->dispatch($command);

        return new JsonResponse([], Response::HTTP_CREATED);
    }
}
