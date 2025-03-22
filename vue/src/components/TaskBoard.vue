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
          @click="loadTasks"
          tooltip="Refresh Tasks"
          tooltipOptions="{ position: 'bottom' }"
        />
        <Button 
          label="Create Task" 
          icon="pi pi-plus" 
          severity="success"
          disabled
          tooltip="Coming soon in future version"
          tooltipOptions="{ position: 'bottom' }"
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
      @task-updated="handleTaskUpdated"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { Task, TaskStatus } from '../types/Task';
import { taskApi } from '../api/taskApi';
import TaskColumn from './TaskColumn.vue';
import TaskDetail from './TaskDetail.vue';
import Button from 'primevue/button';
import ProgressSpinner from 'primevue/progressspinner';
import { useToast } from 'primevue/usetoast';

const toast = useToast();
const loading = ref(true);
const tasks = ref<Task[]>([]);
const statuses: TaskStatus[] = ['To Do', 'In Progress', 'Review', 'Done'];

// Task detail modal state
const taskDetailVisible = ref(false);
const selectedTask = ref<Task | null>(null);

// Get tasks for a specific status
const getTasksByStatus = (status: TaskStatus): Task[] => {
  return tasks.value.filter(task => task.status === status);
};

// Handle task drop event
const handleTaskDropped = async (task: Task, newStatus: TaskStatus) => {
  try {
    // Optimistically update UI
    const taskIndex = tasks.value.findIndex(t => t.id === task.id);
    if (taskIndex !== -1) {
      // Create a new array to trigger reactivity
      const updatedTasks = [...tasks.value];
      updatedTasks[taskIndex] = {
        ...updatedTasks[taskIndex],
        status: newStatus
      };
      tasks.value = updatedTasks;
    }
    
    // Call API to update task
    await taskApi.updateTaskStatus(task.id, newStatus);
    
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

// Load tasks from API
const loadTasks = async () => {
  loading.value = true;
  try {
    tasks.value = await taskApi.getAllTasks();
  } catch (error) {
    console.error('Error loading tasks:', error);
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Failed to load tasks',
      life: 3000
    });
  } finally {
    loading.value = false;
  }
};

// Open task detail modal
const openTaskDetail = (task: Task) => {
  selectedTask.value = task;
  taskDetailVisible.value = true;
};

// Handle updated task from the modal
const handleTaskUpdated = (updatedTask: Task) => {
  // Find and update the task in our local state
  const taskIndex = tasks.value.findIndex(t => t.id === updatedTask.id);
  if (taskIndex !== -1) {
    // Create a new array to trigger reactivity
    const updatedTasks = [...tasks.value];
    updatedTasks[taskIndex] = updatedTask;
    tasks.value = updatedTasks;
    
    // Update the selected task reference to reflect changes
    selectedTask.value = updatedTask;
  }
};

// Load tasks on component mount
onMounted(() => {
  loadTasks();
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
