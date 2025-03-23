import { Task, TaskStatus, TaskType, TaskPriority } from '../types/Task';

/**
 * API client for Task-related operations
 */
class TaskApiClient {
  private baseUrl: string;
  private headers: HeadersInit;
  
  constructor() {
    // Base URL for the API - adjust as needed for your environment
    this.baseUrl = 'http://localhost/api';
    this.headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
  }
  
  /**
   * Helper method to handle API responses
   */
  private async handleResponse<T>(response: Response): Promise<T> {
    if (!response.ok) {
      const errorData = await response.json().catch(() => ({}));
      throw new Error(
        `API error: ${response.status} ${response.statusText} - ${JSON.stringify(errorData)}`
      );
    }
    
    return await response.json() as T;
  }
  
  /**
   * Get all tasks
   * @returns Promise resolving to an array of Task objects
   */
  async getAllTasks(): Promise<Task[]> {
    try {
      const response = await fetch(`${this.baseUrl}/tasks`, {
        method: 'GET',
        headers: this.headers,
      });
      
      return this.handleResponse<Task[]>(response);
    } catch (error) {
      console.error('Error fetching tasks:', error);
      throw error;
    }
  }
  
  /**
   * Get a specific task by ID
   * @param taskId - The ID of the task to retrieve
   * @returns Promise resolving to a Task object
   */
  async getTaskById(taskId: number): Promise<Task> {
    try {
      const response = await fetch(`${this.baseUrl}/tasks/${taskId}`, {
        method: 'GET',
        headers: this.headers,
      });
      
      return this.handleResponse<Task>(response);
    } catch (error) {
      console.error(`Error fetching task ${taskId}:`, error);
      throw error;
    }
  }
  
  /**
   * Update task status
   * @param taskId - The ID of the task to update
   * @param newStatus - The new status for the task
   * @returns Promise resolving to the updated Task
   */
  async updateTaskStatus(taskId: number, newStatus: TaskStatus): Promise<Task> {
    try {
      const response = await fetch(`${this.baseUrl}/tasks/${taskId}/status`, {
        method: 'PATCH',
        headers: this.headers,
        body: JSON.stringify({ status: newStatus }),
      });
      
      return this.handleResponse<Task>(response);
    } catch (error) {
      console.error(`Error updating task ${taskId} status:`, error);
      throw error;
    }
  }
  
  /**
   * Update a task
   * @param taskId - The ID of the task to update
   * @param taskData - Partial task data containing fields to update
   * @returns Promise resolving to the updated Task
   */
  async updateTask(taskId: number, taskData: Partial<Task>): Promise<Task> {
    try {
      const response = await fetch(`${this.baseUrl}/tasks/${taskId}`, {
        method: 'PUT',
        headers: this.headers,
        body: JSON.stringify(taskData),
      });
      
      return this.handleResponse<Task>(response);
    } catch (error) {
      console.error(`Error updating task ${taskId}:`, error);
      throw error;
    }
  }
  
  /**
   * Create a new task
   * @param taskData - Data for the new task
   * @returns Promise resolving to the created Task
   */
  async createTask(taskData: Partial<Task>): Promise<Task> {
    try {
      const response = await fetch(`${this.baseUrl}/tasks`, {
        method: 'POST',
        headers: this.headers,
        body: JSON.stringify(taskData),
      });
      
      return this.handleResponse<Task>(response);
    } catch (error) {
      console.error('Error creating task:', error);
      throw error;
    }
  }
  
  /**
   * Delete a task
   * @param taskId - The ID of the task to delete
   * @returns Promise resolving to void
   */
  async deleteTask(taskId: number): Promise<void> {
    try {
      const response = await fetch(`${this.baseUrl}/tasks/${taskId}`, {
        method: 'DELETE',
        headers: this.headers,
      });
      
      if (!response.ok) {
        await this.handleResponse(response);
      }
    } catch (error) {
      console.error(`Error deleting task ${taskId}:`, error);
      throw error;
    }
  }
  
  /**
   * Get tasks by tag
   * @param tag - The tag to filter tasks by
   * @returns Promise resolving to an array of Task objects
   */
  async getTasksByTag(tag: string): Promise<Task[]> {
    try {
      const response = await fetch(`${this.baseUrl}/tasks/tag/${encodeURIComponent(tag)}`, {
        method: 'GET',
        headers: this.headers,
      });
      
      return this.handleResponse<Task[]>(response);
    } catch (error) {
      console.error(`Error fetching tasks with tag ${tag}:`, error);
      throw error;
    }
  }
}

// Create a singleton instance
export const realTaskApi = new TaskApiClient();
