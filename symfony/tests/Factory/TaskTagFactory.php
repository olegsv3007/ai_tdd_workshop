<?php

namespace App\Tests\Factory;

use App\Domain\Entity\Task;
use App\Domain\Entity\TaskTag;

/**
 * Factory for TaskTag entities
 * 
 * @extends AbstractFactory<TaskTag>
 */
class TaskTagFactory extends AbstractFactory
{
    private static array $tagNames = [
        'frontend', 'backend', 'bugfix', 'feature', 'refactoring',
        'optimization', 'security', 'documentation', 'testing', 'UX/UI',
        'performance', 'database', 'API', 'DevOps', 'design',
        'mobile', 'accessibility', 'integration', 'maintenance', 'analytics'
    ];
    
    /**
     * Get the default attributes for the TaskTag entity
     * 
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    protected function getDefaults(array $attributes = []): array
    {
        return [
            'name' => $attributes['name'] ?? $this->faker->randomElement(self::$tagNames),
            'task' => $attributes['task'] ?? null,
        ];
    }
    
    /**
     * Instantiate a TaskTag entity with the given attributes
     * 
     * @param array<string, mixed> $attributes
     * @return TaskTag
     */
    protected function instantiate(array $attributes): object
    {
        $tag = new TaskTag($attributes['name']);
        
        if ($attributes['task'] instanceof Task) {
            $tag->setTask($attributes['task']);
        }
        
        return $tag;
    }
}
