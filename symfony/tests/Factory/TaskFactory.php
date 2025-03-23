<?php

namespace App\Tests\Factory;

use App\Domain\Entity\Task;
use App\Domain\Enum\TaskPriority;
use App\Domain\Enum\TaskStatus;
use App\Domain\Enum\TaskType;

/**
 * Factory for Task entities
 * 
 * @extends AbstractFactory<Task>
 */
class TaskFactory extends AbstractFactory
{
    /**
     * Get the default attributes for the Task entity
     * 
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    protected function getDefaults(array $attributes = []): array
    {
        return [
            'title' => $attributes['title'] ?? $this->faker->sentence(4),
            'description' => $attributes['description'] ?? $this->faker->paragraph(),
            'status' => $attributes['status'] ?? TaskStatus::TODO,
            'type' => $attributes['type'] ?? TaskType::TASK,
            'priority' => $attributes['priority'] ?? TaskPriority::MEDIUM,
            'reporter' => $attributes['reporter'] ?? $this->faker->email(),
            'assignee' => $attributes['assignee'] ?? $this->faker->optional(0.8)->email(),
            'estimatedHours' => $attributes['estimatedHours'] ?? $this->faker->optional(0.7)->numberBetween(1, 40),
        ];
    }
    
    /**
     * Instantiate a Task entity with the given attributes
     * 
     * @param array<string, mixed> $attributes
     * @return Task
     */
    protected function instantiate(array $attributes): object
    {
        return new Task(
            $attributes['title'],
            $attributes['description'],
            $attributes['status'],
            $attributes['type'],
            $attributes['priority'],
            $attributes['reporter'],
            $attributes['assignee'],
            $attributes['estimatedHours']
        );
    }
}
