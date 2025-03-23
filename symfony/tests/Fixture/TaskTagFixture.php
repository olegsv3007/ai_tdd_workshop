<?php

namespace App\Tests\Fixture;

use App\Domain\Entity\TaskTag;
use App\Tests\Factory\FactoryRegistry;
use App\Tests\Factory\TaskTagFactory;

class TaskTagFixture extends AbstractFixture
{
    private ?string $tagName = null;
    
    /**
     * Set a specific tag name
     * 
     * @param string $tagName Specific tag name to use
     * @return self
     */
    public function withName(string $tagName): self
    {
        $this->tagName = $tagName;
        return $this;
    }
    
    /**
     * Load the fixture and return the created TaskTag entity
     * 
     * @return TaskTag The tag entity created by the fixture
     */
    public function load(): object
    {
        // Create factory registry
        $factoryRegistry = new FactoryRegistry($this->container);
        
        // Get tag factory
        /** @var TaskTagFactory $tagFactory */
        $tagFactory = $factoryRegistry->getFactory(TaskTag::class);
        
        // Create attributes array with tag name if provided
        $attributes = [];
        if ($this->tagName !== null) {
            $attributes['name'] = $this->tagName;
        }
        
        // Create a tag using the factory
        return $tagFactory->create($attributes);
    }
}
