<template>
  <div class="taskboard">
    <div class="taskboard-header flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="m-0">Task Dashboard</h1>
        <p class="text-color-secondary mt-1 mb-0">Manage and track your team's tasks</p>
      </div>
      <div class="flex gap-2">
        <Button 
          icon="pi pi-refresh" 
          outlined 
          :loading="loading" 
          @click="refreshTasks"
          tooltip="Refresh Tasks"
          tooltipOptions="{ position: 'bottom' }"
        />
        <Button 
          label="Create Task" 
          icon="pi pi-plus" 
          severity="success"
          @click="createNewTask"
        />
      </div>
    </div>

    <div v-if="loading" class="flex justify-content-center align-items-center" style="height: 300px">
      <ProgressSpinner />
    </div>
    
    <div v-else class="taskboard-columns grid">
      <div v-for="status in statuses" :key="status" class="col-12 md:col-6 lg:col-3 p-2">
        <TaskColumn 
          :status="status" 
          :tasks="getTasksByStatus(status)"
          @task-dropped="handleTaskDropped"
          @task-click="openTaskDetail" 
        />
      </div>
    </div>
    
    <!-- Task Detail Modal -->
    <TaskDetail
      :visible="taskDetailVisible"
      @update:visible="taskDetailVisible = $event"
      :task="selectedTask"
      :is-creating="isCreatingTask"
      @task-updated="handleTaskUpdated"
      @task-created="handleTaskCreated"
      @task-deleted="handleTaskDeleted"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Task, TaskStatus } from '../types/Task';
import { useTasks } from '../composables/useTasks';
import TaskColumn from './TaskColumn.vue';
import TaskDetail from './TaskDetail.vue';
import Button from 'primevue/button';
import ProgressSpinner from 'primevue/progressspinner';
import { useToast } from 'primevue/usetoast';

const toast = useToast();
const statuses: TaskStatus[] = ['To Do', 'In Progress', 'Review', 'Done'];

// Use the tasks composable
const {
  tasks,
  isLoading: loading,
  error,
  loadTasks,
  updateTaskStatus,
  createTask,
  updateTask,
  deleteTask
} = useTasks();

// Task detail modal state
const taskDetailVisible = ref(false);
const selectedTask = ref<Task | null>(null);
const isCreatingTask = ref(false);

// Get tasks for a specific status
const getTasksByStatus = (status: TaskStatus): Task[] => {
  return tasks.value.filter(task => task.status === status);
};

// Handle task drop event
const handleTaskDropped = async (task: Task, newStatus: TaskStatus) => {
  try {
    // Call API to update task status
    await updateTaskStatus(task.id, newStatus);
    
    toast.add({
      severity: 'success',
      summary: 'Task Updated',
      detail: `Task #${task.id} moved to ${newStatus}`,
      life: 3000
    });
  } catch (error) {
    console.error('Error updating task:', error);
    
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Failed to update task status',
      life: 3000
    });
    
    // Reload tasks to reset state
    loadTasks();
  }
};

// Load tasks from API - this is now handled by the useTasks composable
const refreshTasks = async () => {
  try {
    await loadTasks();
  } catch (error) {
    console.error('Error loading tasks:', error);
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Failed to load tasks',
      life: 3000
    });
  }
};

// Open task detail modal
const openTaskDetail = (task: Task) => {
  selectedTask.value = task;
  isCreatingTask.value = false;
  taskDetailVisible.value = true;
};

// Open create task modal
const createNewTask = () => {
  selectedTask.value = null;
  isCreatingTask.value = true;
  taskDetailVisible.value = true;
};

// Handle updated task from the modal
const handleTaskUpdated = async (updatedTask: Task) => {
  try {
    // The updateTask method in useTasks will handle updating the local state
    await updateTask(updatedTask.id, updatedTask);
    
    // Update the selected task reference to reflect changes
    selectedTask.value = updatedTask;
  } catch (error) {
    console.error('Error updating task:', error);
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Failed to update task',
      life: 3000
    });
  }

  await loadTasks();
};

// Handle newly created task from the modal
const handleTaskCreated = async (newTask: Task) => {
  try {
    // The createTask method in useTasks will handle adding to the local state
    const createdTask = await createTask(newTask);
    
    toast.add({
      severity: 'success',
      summary: 'Task Created',
      detail: `Task #${createdTask.id} has been created successfully`,
      life: 3000
    });
  } catch (error) {
    console.error('Error creating task:', error);
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Failed to create task',
      life: 3000
    });
  }

  await loadTasks();
};

// Handle deleted task from the modal
const handleTaskDeleted = async (taskId: number) => {
  try {
    // The deleteTask method in useTasks will handle removing from the local state
    await deleteTask(taskId);
    
    toast.add({
      severity: 'info',
      summary: 'Task Deleted',
      detail: `Task #${taskId} has been removed from the board`,
      life: 3000
    });
  } catch (error) {
    console.error('Error deleting task:', error);
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Failed to delete task',
      life: 3000
    });
  }

  await loadTasks();
};

// Load tasks on component mount
onMounted(() => {
  refreshTasks();
});
</script>

<style scoped>
.taskboard {
  padding: 1rem;
}

.taskboard-columns {
  min-height: calc(100vh - 180px);
}

@media (max-width: 768px) {
  .taskboard-columns {
    flex-direction: column;
  }
}
</style>
