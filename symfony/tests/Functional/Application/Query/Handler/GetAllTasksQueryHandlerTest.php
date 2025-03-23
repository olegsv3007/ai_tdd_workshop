<?php

namespace App\Tests\Functional\Application\Query\Handler;

use App\Application\Bus\QueryBusContract;
use App\Application\Query\GetAllTasksQuery;
use App\Domain\Entity\Task;
use App\Tests\Fixture\TaskFixture;
use App\Tests\Fixture\TaskWithCustomTagsFixture;
use App\Tests\Functional\FunctionalTestCase;

class GetAllTasksQueryHandlerTest extends FunctionalTestCase
{
    private QueryBusContract $queryBus;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->queryBus = $this->getQueryBus();
    }
    
    public function testGetAllTasksQueryReturnsAllTasks(): void
    {
        $task1 = $this->loadFixture(TaskFixture::class, Task::class);
        $task2 = $this->loadFixture(TaskWithCustomTagsFixture::class, Task::class);
        
        $expectedTaskIds = [
            $task1->getId(),
            $task2->getId(),
        ];

        $tasks = $this->queryBus->query(new GetAllTasksQuery());
        $actualTaskIds = array_map(fn(Task $task) => $task->getId(), $tasks);

        self::assertCount(2, $tasks, 'Query should return exactly 2 tasks');
        self::assertEmpty(array_diff($expectedTaskIds, $actualTaskIds));
    }
}
