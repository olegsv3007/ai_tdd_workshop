<?php

namespace App\Tests\Acceptance\Controller;

use App\Domain\Entity\Task;
use App\Tests\Assertion\Constraint\TaskApiResponseConstraint;
use App\Tests\Fixture\TaskFixture;
use App\Tests\Fixture\TaskWithCustomTagsFixture;
use App\Tests\Acceptance\AcceptanceTestCase;

class TaskControllerTest extends AcceptanceTestCase
{
    public function testGetAllTasks(): void
    {
        $task1 = $this->loadFixture(TaskFixture::class, Task::class);
        $task2 = $this->loadFixture(TaskWithCustomTagsFixture::class, Task::class);
        $expectedTasks = [$task1, $task2];

        $client = self::getClient();
        $client->request('GET', '/api/tasks');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('Content-Type', 'application/json');
        self::assertThat($client->getResponse(), new TaskApiResponseConstraint($expectedTasks));
    }
}
