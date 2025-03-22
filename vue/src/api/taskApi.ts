import { Task, TaskStatus, TaskType, TaskPriority } from '../types/Task';

// Helper function to generate random tasks
const generateRandomTasks = (count: number): Task[] => {
  const statuses: TaskStatus[] = ['To Do', 'In Progress', 'Review', 'Done'];
  const types: TaskType[] = ['Bug', 'Feature', 'Epic', 'Story', 'Task'];
  const priorities: TaskPriority[] = ['Low', 'Medium', 'High', 'Critical'];
  const users = [
    'John Smith', 
    'Alice Johnson', 
    'Robert Brown', 
    'Emily Davis', 
    'Michael Wilson',
    'Sarah Martinez'
  ];
  
  const tags = [
    'frontend', 'backend', 'database', 'UI', 'UX', 'performance', 
    'security', 'testing', 'documentation', 'refactoring'
  ];

  return Array.from({ length: count }, (_, i) => {
    const createdDate = new Date(Date.now() - Math.floor(Math.random() * 30) * 24 * 60 * 60 * 1000);
    const updatedDate = new Date(createdDate.getTime() + Math.floor(Math.random() * 10) * 24 * 60 * 60 * 1000);
    
    // Generate 0-3 random tags
    const taskTags: string[] = [];
    const tagCount = Math.floor(Math.random() * 4);
    for (let j = 0; j < tagCount; j++) {
      const randomTag = tags[Math.floor(Math.random() * tags.length)];
      if (!taskTags.includes(randomTag)) {
        taskTags.push(randomTag);
      }
    }

    return {
      id: i + 1,
      title: `Task-${i + 1}: ${getRandomTaskTitle()}`,
      description: getRandomTaskDescription(),
      status: statuses[Math.floor(Math.random() * statuses.length)],
      type: types[Math.floor(Math.random() * types.length)],
      priority: priorities[Math.floor(Math.random() * priorities.length)],
      assignee: Math.random() > 0.2 ? users[Math.floor(Math.random() * users.length)] : null,
      reporter: users[Math.floor(Math.random() * users.length)],
      createdAt: createdDate.toISOString(),
      updatedAt: updatedDate.toISOString(),
      estimatedHours: Math.random() > 0.3 ? Math.floor(Math.random() * 40) + 1 : null,
      tags: taskTags
    };
  });
};

// Helper to generate random task titles
const getRandomTaskTitle = (): string => {
  const actions = ['Implement', 'Fix', 'Update', 'Refactor', 'Create', 'Remove', 'Test', 'Document', 'Optimize'];
  const subjects = ['login form', 'dashboard', 'user profile', 'search functionality', 'API endpoint', 
    'database schema', 'navigation menu', 'payment process', 'error handling', 'notification system', 
    'authentication', 'file upload', 'performance issue', 'UI component', 'responsive design'];
  
  return `${actions[Math.floor(Math.random() * actions.length)]} ${subjects[Math.floor(Math.random() * subjects.length)]}`;
};

// Helper to generate random task descriptions
const getRandomTaskDescription = (): string => {
  const descriptions = [
    'This task involves implementing a new feature that will improve user experience.',
    'Fix the bug that causes the application to crash when users perform specific actions.',
    'Update the existing functionality to match the new design specifications.',
    'Refactor the code to improve maintainability and reduce technical debt.',
    'Create a new component that will be reused across the application.',
    'Remove deprecated features that are no longer needed.',
    'Test the functionality to ensure it works as expected across different browsers and devices.',
    'Document the API endpoints for developers to understand how to use them properly.',
    'Optimize the database queries to improve performance.',
    'Implement responsive design for mobile users.',
    'Add proper error handling and user feedback for failed operations.',
    'Update dependencies to the latest versions and ensure compatibility.',
    'Set up monitoring and logging for better debugging.',
    'Implement accessibility features for users with disabilities.',
    'Improve form validation and error messages for better user experience.'
  ];
  
  return descriptions[Math.floor(Math.random() * descriptions.length)];
};

// Mock API client
export class TaskApiClient {
  private tasks: Task[];

  constructor() {
    this.tasks = generateRandomTasks(20);
  }

  async getAllTasks(): Promise<Task[]> {
    // Simulate API delay
    await new Promise(resolve => setTimeout(resolve, 500));
    return this.tasks;
  }

  async updateTaskStatus(taskId: number, newStatus: TaskStatus): Promise<Task> {
    // Simulate API delay
    await new Promise(resolve => setTimeout(resolve, 300));
    
    const taskIndex = this.tasks.findIndex(task => task.id === taskId);
    if (taskIndex === -1) {
      throw new Error(`Task with ID ${taskId} not found`);
    }
    
    // Create a new task with updated status
    const updatedTask = {
      ...this.tasks[taskIndex],
      status: newStatus,
      updatedAt: new Date().toISOString()
    };
    
    // Update the tasks array
    this.tasks[taskIndex] = updatedTask;
    
    return updatedTask;
  }

  async updateTask(taskId: number, taskData: Partial<Task>): Promise<Task> {
    // Simulate API delay
    await new Promise(resolve => setTimeout(resolve, 400));
    
    const taskIndex = this.tasks.findIndex(task => task.id === taskId);
    if (taskIndex === -1) {
      throw new Error(`Task with ID ${taskId} not found`);
    }
    
    // Create a new task with updated data
    const updatedTask = {
      ...this.tasks[taskIndex],
      ...taskData,
      updatedAt: new Date().toISOString()
    };
    
    // Update the tasks array
    this.tasks[taskIndex] = updatedTask;
    
    return updatedTask;
  }
}

// Create a singleton instance
export const taskApi = new TaskApiClient();
