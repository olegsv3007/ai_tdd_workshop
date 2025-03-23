import { ref, Ref } from 'vue';
import { realTaskApi } from '../api/realTaskApi';
import { Task, TaskStatus } from '../types/Task';

/**
 * Composable for managing tasks
 */
export function useTasks() {
  const tasks: Ref<Task[]> = ref([]);
  const isLoading: Ref<boolean> = ref(false);
  const error: Ref<string | null> = ref(null);
  const currentTask: Ref<Task | null> = ref(null);
  
  /**
   * Load all tasks
   */
  const loadTasks = async () => {
    isLoading.value = true;
    error.value = null;
    
    try {
      tasks.value = await realTaskApi.getAllTasks();
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to load tasks';
      console.error(error.value);
    } finally {
      isLoading.value = false;
    }
  };
  
  /**
   * Load a specific task by ID
   */
  const loadTask = async (taskId: number) => {
    isLoading.value = true;
    error.value = null;
    
    try {
      currentTask.value = await realTaskApi.getTaskById(taskId);
    } catch (err) {
      error.value = err instanceof Error ? err.message : `Failed to load task ${taskId}`;
      console.error(error.value);
    } finally {
      isLoading.value = false;
    }
  };
  
  /**
   * Create a new task
   */
  const createTask = async (taskData: Partial<Task>) => {
    isLoading.value = true;
    error.value = null;
    
    try {
      const newTask = await realTaskApi.createTask(taskData);
      tasks.value = [...tasks.value, newTask];
      return newTask;
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to create task';
      console.error(error.value);
      throw err;
    } finally {
      isLoading.value = false;
    }
  };
  
  /**
   * Update an existing task
   */
  const updateTask = async (taskId: number, taskData: Partial<Task>) => {
    isLoading.value = true;
    error.value = null;
    
    try {
      const updatedTask = await realTaskApi.updateTask(taskId, taskData);
      
      // Update the tasks array with the updated task
      const index = tasks.value.findIndex(task => task.id === taskId);
      if (index !== -1) {
        tasks.value = [
          ...tasks.value.slice(0, index),
          updatedTask,
          ...tasks.value.slice(index + 1)
        ];
      }
      
      // Update currentTask if it's the same task
      if (currentTask.value?.id === taskId) {
        currentTask.value = updatedTask;
      }
      
      return updatedTask;
    } catch (err) {
      error.value = err instanceof Error ? err.message : `Failed to update task ${taskId}`;
      console.error(error.value);
      throw err;
    } finally {
      isLoading.value = false;
    }
  };
  
  /**
   * Update task status
   */
  const updateTaskStatus = async (taskId: number, newStatus: TaskStatus) => {
    isLoading.value = true;
    error.value = null;
    
    try {
      const updatedTask = await realTaskApi.updateTaskStatus(taskId, newStatus);
      
      // Update the tasks array with the updated task
      const index = tasks.value.findIndex(task => task.id === taskId);
      if (index !== -1) {
        tasks.value = [
          ...tasks.value.slice(0, index),
          updatedTask,
          ...tasks.value.slice(index + 1)
        ];
      }
      
      // Update currentTask if it's the same task
      if (currentTask.value?.id === taskId) {
        currentTask.value = updatedTask;
      }
      
      return updatedTask;
    } catch (err) {
      error.value = err instanceof Error ? err.message : `Failed to update task ${taskId} status`;
      console.error(error.value);
      throw err;
    } finally {
      isLoading.value = false;
    }
  };
  
  /**
   * Delete a task
   */
  const deleteTask = async (taskId: number) => {
    isLoading.value = true;
    error.value = null;
    
    try {
      await realTaskApi.deleteTask(taskId);
      
      // Remove the task from the tasks array
      tasks.value = tasks.value.filter(task => task.id !== taskId);
      
      // Clear currentTask if it's the same task
      if (currentTask.value?.id === taskId) {
        currentTask.value = null;
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : `Failed to delete task ${taskId}`;
      console.error(error.value);
      throw err;
    } finally {
      isLoading.value = false;
    }
  };
  
  /**
   * Get tasks by tag
   */
  const getTasksByTag = async (tag: string) => {
    isLoading.value = true;
    error.value = null;
    
    try {
      tasks.value = await realTaskApi.getTasksByTag(tag);
      return tasks.value;
    } catch (err) {
      error.value = err instanceof Error ? err.message : `Failed to get tasks with tag ${tag}`;
      console.error(error.value);
      throw err;
    } finally {
      isLoading.value = false;
    }
  };
  
  return {
    tasks,
    currentTask,
    isLoading,
    error,
    loadTasks,
    loadTask,
    createTask,
    updateTask,
    updateTaskStatus,
    deleteTask,
    getTasksByTag
  };
}
