<?php

namespace App\Tests\Fixture;

use App\Domain\Entity\Task;
use App\Domain\Entity\TaskTag;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Tests\Factory\FactoryRegistry;

class TaskFixture extends AbstractFixture
{
    private array $defaultTags = ['testing', 'fixture', 'automated'];
    
    /**
     * Load the fixture and return the created Task entity
     * 
     * @return Task The task entity created by the fixture
     */
    public function load(): object
    {
        $factoryRegistry = new FactoryRegistry($this->container);
        
        $taskFactory = $factoryRegistry->getFactory(Task::class);
        
        $task = $taskFactory->create();
        
        $taskRepository = $this->container->get(TaskRepositoryInterface::class);

        $tagFactory = $factoryRegistry->getFactory(TaskTag::class);
        
        foreach ($this->defaultTags as $tagName) {
            $tag = $tagFactory->make(['name' => $tagName]);
            $task->addTag($tag);
        }
        
        $taskRepository->save($task);
        
        return $task;
    }
}
