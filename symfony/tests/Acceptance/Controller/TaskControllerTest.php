<?php

namespace App\Tests\Acceptance\Controller;

use App\Domain\Entity\Task;
use App\Domain\Entity\TaskTag;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Tests\Assertion\Constraint\TaskApiResponseConstraint;
use App\Tests\Fixture\TaskFixture;
use App\Tests\Fixture\TaskWithCustomTagsFixture;
use App\Tests\Acceptance\AcceptanceTestCase;

class TaskControllerTest extends AcceptanceTestCase
{
    private TaskRepositoryInterface $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskRepository = self::getService(TaskRepositoryInterface::class);
    }

    public function testGetAllTasks(): void
    {
        $task1 = $this->loadFixture(TaskFixture::class, Task::class);
        $task2 = $this->loadFixture(TaskWithCustomTagsFixture::class, Task::class);
        $expectedTasks = [$task1, $task2];

        $this->client->request('GET', '/api/tasks');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('Content-Type', 'application/json');
        self::assertThat($this->client->getResponse(), new TaskApiResponseConstraint($expectedTasks));
    }

    public function testCreateTask(): void
    {
        $requestBody = [
            'title' => 'Task Title',
            'description' => 'Task Description',
            'type' => 'Task',
            'priority' => 'Medium',
            'status' => 'To Do',
            'reporter' => 'Reporter User',
            'assignee' => 'Assignee User',
            'estimatedHours' => 3.0,
            'tags' => ['tag1', 'tag2'],
        ];

        $this->client->request(
            'POST',
            '/api/tasks',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($requestBody)
        );

        $createdTask = $this->taskRepository->findAll()[0];

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('Content-Type', 'application/json');
        self::assertTaskCreatedContainsDataFromRequest($createdTask, $requestBody);
    }

    private static function assertTaskCreatedContainsDataFromRequest(Task $task, array $requestBody): void
    {
        self::assertSame($requestBody['title'], $task->getTitle());
        self::assertSame($requestBody['description'], $task->getDescription());
        self::assertSame($requestBody['type'], $task->getType()->value);
        self::assertSame($requestBody['priority'], $task->getPriority()->value);
        self::assertSame($requestBody['status'], $task->getStatus()->value);
        self::assertSame($requestBody['reporter'], $task->getReporter());
        self::assertSame($requestBody['assignee'], $task->getAssignee());
        self::assertSame($requestBody['estimatedHours'], $task->getEstimatedHours());

        self::assertCount(count($requestBody['tags']), $task->getTags());
        self::assertTaskHasTags($task, $requestBody['tags']);
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
}
