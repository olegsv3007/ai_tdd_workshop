<?php

namespace App\Tests\Fixture;

use App\Domain\Entity\Task;
use App\Domain\Entity\TaskTag;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\Repository\TaskTagRepositoryInterface;
use App\Tests\Factory\FactoryRegistry;
use App\Tests\Factory\TaskFactory;
use App\Tests\Factory\TaskTagFactory;

class TaskWithCustomTagsFixture extends AbstractFixture
{
    /**
     * @var string[] Tags to add to the task
     */
    private array $tags = ['custom', 'tags'];
    
    /**
     * Set custom tags for the fixture
     * 
     * @param string[] $tags Tags to add to the task
     * @return self
     */
    public function withTags(array $tags): self
    {
        $this->tags = $tags;
        return $this;
    }
    
    /**
     * Load the fixture and return the created Task entity with custom tags
     * 
     * @return Task The task entity created by the fixture
     */
    public function load(): object
    {
        // Create factory registry
        $factoryRegistry = new FactoryRegistry($this->container);
        
        // Get task factory
        /** @var TaskFactory $taskFactory */
        $taskFactory = $factoryRegistry->getFactory(Task::class);
        
        // Create a task using the factory
        $task = $taskFactory->create();
        
        // Get tag factory
        /** @var TaskTagFactory $tagFactory */
        $tagFactory = $factoryRegistry->getFactory(TaskTag::class);
        
        // Get repositories
        $taskRepository = $this->container->get(TaskRepositoryInterface::class);
        $tagRepository = $this->container->get(TaskTagRepositoryInterface::class);
        
        // Add tags to the task
        foreach ($this->tags as $tagName) {
            $tag = $tagFactory->make(['name' => $tagName]);
            $task->addTag($tag);
        }
        
        // Save the task with its tags
        $taskRepository->save($task);
        
        return $task;
    }
}
