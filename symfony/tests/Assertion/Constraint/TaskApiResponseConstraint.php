<?php

namespace App\Tests\Assertion\Constraint;

use App\Domain\Entity\Task;
use App\Domain\Entity\TaskTag;
use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\HttpFoundation\Response;

class TaskApiResponseConstraint extends Constraint
{
    /**
     * @var Task[] Expected tasks that should be in the response
     */
    private array $expectedTasks;

    /**
     * @param Task[] $expectedTasks
     */
    public function __construct(array $expectedTasks)
    {
        $this->expectedTasks = $expectedTasks;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return 'is a valid task API response';
    }

    /**
     * {@inheritdoc}
     */
    private string $failureReason = '';

    protected function matches($other): bool
    {
        if (!$other instanceof Response) {
            $this->failureReason = 'Response is not an instance of Symfony\Component\HttpFoundation\Response';
            return false;
        }

        // Check response status code
        if ($other->getStatusCode() !== Response::HTTP_OK) {
            $this->failureReason = sprintf('Response status code is %d, expected %d', $other->getStatusCode(), Response::HTTP_OK);
            return false;
        }

        // Check content type
        if ($other->headers->get('Content-Type') !== 'application/json') {
            $this->failureReason = sprintf('Response content type is %s, expected application/json', $other->headers->get('Content-Type'));
            return false;
        }

        // Check that content is valid JSON
        $content = $other->getContent();
        if ($content === false) {
            $this->failureReason = 'Response content is empty';
            return false;
        }

        $decodedContent = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->failureReason = sprintf('Response content is not valid JSON: %s', json_last_error_msg());
            return false;
        }
        
        // Debug the response content - uncomment for debugging
        // echo "\nResponse content: " . $content . "\n";

        // Check count of items
        if (count($decodedContent) !== count($this->expectedTasks)) {
            $this->failureReason = sprintf('Response contains %d items, expected %d', count($decodedContent), count($this->expectedTasks));
            return false;
        }

        // Check task structure
        foreach ($decodedContent as $index => $task) {
            if (!$this->hasValidTaskStructure($task, $index)) {
                return false;
            }
        }

        // Check that all expected tasks are in the response
        $taskIds = array_map(function (array $task) {
            return $task['id'];
        }, $decodedContent);

        foreach ($this->expectedTasks as $expectedTask) {
            if (!in_array($expectedTask->getId(), $taskIds, true)) {
                $this->failureReason = sprintf('Expected task with ID %d not found in response', $expectedTask->getId());
                return false;
            }

            // Find the task in the response
            $taskInResponse = null;
            foreach ($decodedContent as $task) {
                if ($task['id'] === $expectedTask->getId()) {
                    $taskInResponse = $task;
                    break;
                }
            }

            if ($taskInResponse === null) {
                $this->failureReason = sprintf('Task with ID %d not found in response', $expectedTask->getId());
                return false;
            }

            // Check that task data matches
            if (!$this->taskDataMatches($expectedTask, $taskInResponse)) {
                // The error message is set in taskDataMatches method
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function failureDescription($other): string
    {
        if (!empty($this->failureReason)) {
            return 'the response ' . $this->toString() . ' (' . $this->failureReason . ')';
        }
        return 'the response ' . $this->toString();
    }

    /**
     * Check if the task has all required fields
     */
    private function hasValidTaskStructure(array $task, int $index): bool
    {
        $requiredFields = [
            'id', 'title', 'description', 'status', 'type', 'priority',
            'assignee', 'reporter', 'createdAt', 'updatedAt', 'estimatedHours', 'tags'
        ];

        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $task)) {
                $this->failureReason = sprintf('Task at index %d is missing required field "%s"', $index, $field);
                return false;
            }
        }

        // Check tags structure
        if (!is_array($task['tags'])) {
            $this->failureReason = sprintf('Tags for task at index %d is not an array', $index);
            return false;
        }

        foreach ($task['tags'] as $tag) {
            if (!array_key_exists('id', $tag) || !array_key_exists('name', $tag)) {
                $this->failureReason = sprintf('Tag for task at index %d is missing required field "id" or "name"', $index);
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the task data in the response matches the expected task entity
     */
    private function taskDataMatches(Task $expectedTask, array $actualTask): bool
    {
        if ($expectedTask->getId() !== $actualTask['id']) {
            $this->failureReason = sprintf('Task ID mismatch: expected %d, got %d', $expectedTask->getId(), $actualTask['id']);
            return false;
        }

        if ($expectedTask->getTitle() !== $actualTask['title']) {
            $this->failureReason = sprintf('Task title mismatch for task ID %d: expected "%s", got "%s"', $expectedTask->getId(), $expectedTask->getTitle(), $actualTask['title']);
            return false;
        }

        if ($expectedTask->getDescription() !== $actualTask['description']) {
            $this->failureReason = sprintf('Task description mismatch for task ID %d', $expectedTask->getId());
            return false;
        }

        if ($expectedTask->getStatus()->value !== $actualTask['status']) {
            $this->failureReason = sprintf('Task status mismatch for task ID %d: expected "%s", got "%s"', $expectedTask->getId(), $expectedTask->getStatus()->value, $actualTask['status']);
            return false;
        }

        if ($expectedTask->getType()->value !== $actualTask['type']) {
            $this->failureReason = sprintf('Task type mismatch for task ID %d: expected "%s", got "%s"', $expectedTask->getId(), $expectedTask->getType()->value, $actualTask['type']);
            return false;
        }

        if ($expectedTask->getPriority()->value !== $actualTask['priority']) {
            $this->failureReason = sprintf('Task priority mismatch for task ID %d: expected "%s", got "%s"', $expectedTask->getId(), $expectedTask->getPriority()->value, $actualTask['priority']);
            return false;
        }

        if ($expectedTask->getAssignee() !== $actualTask['assignee']) {
            $this->failureReason = sprintf('Task assignee mismatch for task ID %d: expected "%s", got "%s"', $expectedTask->getId(), $expectedTask->getAssignee(), $actualTask['assignee']);
            return false;
        }

        if ($expectedTask->getReporter() !== $actualTask['reporter']) {
            $this->failureReason = sprintf('Task reporter mismatch for task ID %d: expected "%s", got "%s"', $expectedTask->getId(), $expectedTask->getReporter(), $actualTask['reporter']);
            return false;
        }

        // Use a small epsilon for floating-point comparison
        if (abs($expectedTask->getEstimatedHours() - $actualTask['estimatedHours']) > 0.0001) {
            $this->failureReason = sprintf('Task estimated hours mismatch for task ID %d: expected %f, got %f', $expectedTask->getId(), $expectedTask->getEstimatedHours(), $actualTask['estimatedHours']);
            return false;
        }

        // Check tags
        return $this->taskTagsMatch($expectedTask->getTags()->toArray(), $actualTask['tags']);
    }

    /**
     * Check if the task tags in the response match the expected tags
     *
     * @param TaskTag[] $expectedTags
     * @param array $actualTags
     */
    private function taskTagsMatch(array $expectedTags, array $actualTags): bool
    {
        if (count($expectedTags) !== count($actualTags)) {
            $this->failureReason = sprintf('Tag count mismatch: expected %d, got %d', count($expectedTags), count($actualTags));
            return false;
        }

        $expectedTagNames = array_map(function (TaskTag $tag) {
            return $tag->getName();
        }, $expectedTags);

        $actualTagNames = array_map(function (array $tag) {
            return $tag['name'];
        }, $actualTags);

        sort($expectedTagNames);
        sort($actualTagNames);

        if ($expectedTagNames !== $actualTagNames) {
            $this->failureReason = sprintf('Tag names mismatch: expected [%s], got [%s]', implode(', ', $expectedTagNames), implode(', ', $actualTagNames));
            return false;
        }
        
        return true;
    }
}
