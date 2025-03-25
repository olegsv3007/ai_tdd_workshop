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
    `This task involves implementing a new feature that will improve user experience. The current workflow is confusing for users, particularly when they need to navigate between different sections. We need to create a more intuitive interface with clear visual cues and simplified navigation paths. This should reduce the learning curve for new users and improve efficiency for experienced users.`,
    
    `Fix the bug that causes the application to crash when users perform specific actions. This issue appears most commonly during file uploads larger than 10MB and when multiple users are accessing the same resource simultaneously. It seems to be related to our resource locking mechanism. The fix should include proper error handling and graceful degradation.`,
    
    `Update the existing functionality to match the new design specifications. The design team has provided updated mockups that feature a more modern aesthetic with increased whitespace, new color palette, and improved typography. This update should maintain all existing functionality while implementing the new visual design across all relevant components.`,
    
    `Refactor the code to improve maintainability and reduce technical debt. The current implementation uses outdated patterns and has grown organically without proper architecture planning. We need to restructure the codebase using a consistent architectural pattern, improve code organization, and add comprehensive documentation for future developers.`,
    
    `Create a new component that will be reused across the application. This component should handle user authentication states, displaying appropriate UI elements based on whether the user is logged in, their permission level, and their account status. It needs to be flexible enough to work in various contexts while maintaining consistent behavior.`,
    
    `Remove deprecated features that are no longer needed. These include the legacy reporting system, the old user profile page, and several unused API endpoints. Before removal, ensure that no part of the application still depends on these features. Create a migration plan for any users who might still be using these features.`,
    
    `Test the functionality to ensure it works as expected across different browsers, devices, and network conditions. Create a comprehensive test suite that covers critical user journeys, edge cases, and performance under load. Document all test procedures to ensure consistency in future testing efforts.`,
    
    `Document the API endpoints for developers to understand how to use them properly. The documentation should include request and response formats, authentication requirements, rate limiting information, and example calls for each endpoint. Organize the documentation in a logical manner and ensure it stays in sync with the actual API implementation.`,
    
    `Optimize the database queries to improve performance. Current page load times exceed our target metrics by 30%. Analyze the most resource-intensive queries and implement optimizations such as proper indexing, query rewriting, and potential caching strategies. Measure the impact of each optimization to ensure we meet our performance targets.`,
    
    `Implement responsive design for mobile users. Our analytics show increasing mobile traffic, but the conversion rate on mobile is 40% lower than desktop. Create a fully responsive layout that provides an optimal experience across all device sizes. Pay special attention to touch interactions, form inputs, and navigation patterns on smaller screens.`,
    
    `Add proper error handling and user feedback for failed operations. Users currently receive generic error messages that don't help them understand or resolve issues. Implement a comprehensive error handling system with user-friendly messages, suggested actions, and appropriate logging for debugging purposes.`,
    
    `Update dependencies to the latest versions and ensure compatibility. We are currently running several libraries with known security vulnerabilities. Create a plan to update all dependencies in phases, testing thoroughly at each stage to identify and resolve any compatibility issues.`,
    
    `Set up monitoring and logging for better debugging. We currently lack visibility into system performance and user-experienced errors in production. Implement a comprehensive monitoring solution that tracks key metrics, logs important events, and alerts the team when issues arise. This should help us identify and resolve problems more quickly.`,
    
    `Implement accessibility features for users with disabilities. Our application doesn't currently meet WCAG standards. Add appropriate ARIA attributes, ensure proper keyboard navigation, implement sufficient color contrast, and test with screen readers. Documentation should be updated to include accessibility requirements for future development.`,
    
    `Improve form validation and error messages for better user experience. Users frequently abandon forms due to confusion about requirements and validation errors. Implement real-time validation with clear, contextual error messages that help users complete forms successfully. Consider adding visual cues and examples for complex input fields.`
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
      tags: taskData.tags?.map(tag => typeof tag === 'string' ? tag : tag.name),
      updatedAt: new Date().toISOString()
    };
    
    // Update the tasks array
    this.tasks[taskIndex] = updatedTask;
    
    return updatedTask;
  }

  async createTask(taskData: Partial<Task>): Promise<Task> {
    // Simulate API delay
    await new Promise(resolve => setTimeout(resolve, 500));
    
    // Generate a new ID (highest ID + 1)
    const newId = this.tasks.length > 0 
      ? Math.max(...this.tasks.map(task => task.id)) + 1 
      : 1;
    
    const now = new Date().toISOString();
    
    // Create new task with defaults
    const newTask: Task = {
      id: newId,
      title: taskData.title || 'New Task',
      description: taskData.description || '',
      status: taskData.status || 'To Do',
      type: taskData.type || 'Task',
      priority: taskData.priority || 'Medium',
      assignee: taskData.assignee || null,
      reporter: taskData.reporter || 'Current User', // In a real app, this would be the current user
      createdAt: now,
      updatedAt: now,
      estimatedHours: taskData.estimatedHours || null,
      tags: taskData.tags || []
    };
    
    // Add to tasks array
    this.tasks.push(newTask);
    
    return newTask;
  }
  
  async deleteTask(taskId: number): Promise<void> {
    // Simulate API delay
    await new Promise(resolve => setTimeout(resolve, 400));
    
    const taskIndex = this.tasks.findIndex(task => task.id === taskId);
    if (taskIndex === -1) {
      throw new Error(`Task with ID ${taskId} not found`);
    }
    
    // Remove the task from the array
    this.tasks.splice(taskIndex, 1);
  }
}

// Create a singleton instance
export const taskApi = new TaskApiClient();
