<?php

namespace App\Infrastructure\DataFixture;

use App\Domain\Entity\Task;
use App\Domain\Entity\TaskTag;
use App\Domain\Enum\TaskPriority;
use App\Domain\Enum\TaskStatus;
use App\Domain\Enum\TaskType;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\Repository\TaskTagRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

class TaskFixture
{
    private const TASK_TITLES = [
        'Implement user authentication',
        'Create dashboard UI',
        'Fix sidebar navigation bug',
        'Optimize database queries',
        'Add export to CSV feature',
        'Refactor legacy code',
        'Setup CI/CD pipeline',
        'Create unit tests for API',
        'Implement search functionality',
        'Design responsive mobile view',
        'Add dark mode theme',
        'Integrate with third-party API',
        'Update documentation',
        'Fix memory leak issue',
        'Implement caching layer',
        'Add data validation',
        'Create admin panel',
        'Set up monitoring and alerts',
        'Implement file upload feature',
        'Optimize frontend performance',
    ];
    
    private const TASK_DESCRIPTIONS = [
        'Implement secure authentication using OAuth 2.0 and JWT tokens for API security.',
        'Design and build a modern dashboard with key metrics and visualizations.',
        'Navigation sidebar does not display correctly in Firefox. Fix layout issues.',
        'Improve query performance by adding indexes and optimizing JOIN operations.',
        'Create a feature to export task data to CSV with filtering options.',
        'Refactor the payment processing module to follow modern design patterns.',
        'Set up GitHub Actions for automated testing and deployment.',
        'Write comprehensive unit tests for the REST API endpoints.',
        'Add global search functionality across all entities with relevance ranking.',
        'Ensure the application is fully responsive on mobile devices.',
        'Implement a user-toggleable dark mode theme with saved preferences.',
        'Integrate with the Stripe payment API for subscription handling.',
        'Update all technical documentation to reflect recent changes.',
        'Investigate and fix memory leaks in the reporting module.',
        'Implement Redis caching for frequently accessed data.',
        'Add frontend and backend validation for all user inputs.',
        'Create an admin panel for user management and system settings.',
        'Set up Prometheus and Grafana for monitoring application health.',
        'Implement secure file upload with virus scanning and validation.',
        'Optimize frontend assets and implement lazy loading for improved performance.',
    ];
    
    private const TASK_TAGS = [
        'frontend', 'backend', 'bugfix', 'feature', 'refactoring',
        'optimization', 'security', 'documentation', 'testing', 'UX/UI',
        'performance', 'database', 'API', 'DevOps', 'design',
        'mobile', 'accessibility', 'integration', 'maintenance', 'analytics'
    ];
    
    private const USERS = [
        'john.doe@example.com',
        'jane.smith@example.com',
        'alex.wilson@example.com',
        'emma.brown@example.com',
        'mike.johnson@example.com',
    ];
    
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private TaskTagRepositoryInterface $taskTagRepository,
        private ManagerRegistry $doctrine
    ) { }
    
    public function load(): void
    {
        $this->doctrine->getConnection()->executeStatement('SET FOREIGN_KEY_CHECKS=0');
        $this->doctrine->getConnection()->executeStatement('TRUNCATE TABLE tasks');
        $this->doctrine->getConnection()->executeStatement('TRUNCATE TABLE task_tags');
        $this->doctrine->getConnection()->executeStatement('SET FOREIGN_KEY_CHECKS=1');
        
        $taskTypes = [TaskType::BUG, TaskType::FEATURE, TaskType::EPIC, TaskType::STORY, TaskType::TASK];
        $taskPriorities = [TaskPriority::LOW, TaskPriority::MEDIUM, TaskPriority::HIGH, TaskPriority::CRITICAL];
        $taskStatuses = [TaskStatus::TODO, TaskStatus::IN_PROGRESS, TaskStatus::REVIEW, TaskStatus::DONE];
        
        for ($i = 0; $i < 20; $i++) {
            $title = self::TASK_TITLES[$i];
            $description = self::TASK_DESCRIPTIONS[$i];
            $type = $taskTypes[array_rand($taskTypes)];
            $priority = $taskPriorities[array_rand($taskPriorities)];
            $status = $taskStatuses[array_rand($taskStatuses)];
            $reporter = self::USERS[array_rand(self::USERS)];
            $assignee = (rand(0, 100) > 20) ? self::USERS[array_rand(self::USERS)] : null;
            $estimatedHours = (rand(0, 100) > 30) ? rand(1, 40) : null;
            
            // Create a task without initial tags collection
            $task = new Task(
                $title,
                $description,
                $status,
                $type,
                $priority,
                $reporter,
                $assignee,
                $estimatedHours
            );
            
            $this->taskRepository->save($task);
            
            // Add random tags (1-3 tags per task)
            $tagCount = rand(1, 3);
            $tagIndexes = array_rand(self::TASK_TAGS, $tagCount);
            
            if (!is_array($tagIndexes)) {
                $tagIndexes = [$tagIndexes];
            }
            
            foreach ($tagIndexes as $tagIndex) {
                $tag = new TaskTag(self::TASK_TAGS[$tagIndex]);
                $task->addTag($tag);
                $this->taskTagRepository->save($tag);
            }
        }
    }
}
