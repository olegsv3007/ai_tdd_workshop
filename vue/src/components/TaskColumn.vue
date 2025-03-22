<template>
  <div 
    class="task-column h-full"
    @dragover.prevent
    @drop="onDrop"
  >
    <div class="column-header p-3 mb-2">
      <h2 class="text-xl font-bold m-0">{{ status }} ({{ tasks.length }})</h2>
    </div>
    
    <div class="task-list p-2">
      <TaskCard 
        v-for="task in tasks" 
        :key="task.id"
        :task="task"
        @dragstart="onDragStart"
        @click="onTaskClick"
        class="mb-3"
      />
      
      <div v-if="tasks.length === 0" class="empty-column p-3 text-center">
        <p class="text-color-secondary">No tasks in this column</p>
        <i class="pi pi-inbox text-4xl text-color-secondary"></i>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';
import { Task, TaskStatus } from '../types/Task';
import TaskCard from './TaskCard.vue';

const props = defineProps<{
  status: TaskStatus;
  tasks: Task[];
}>();

const emit = defineEmits<{
  'task-dropped': [task: Task, newStatus: TaskStatus];
  'task-click': [task: Task];
}>();

const onDragStart = (event: DragEvent, task: Task) => {
  if (event.dataTransfer) {
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', JSON.stringify(task));
  }
};

const onDrop = (event: DragEvent) => {
  if (event.dataTransfer) {
    const taskJson = event.dataTransfer.getData('text/plain');
    const task = JSON.parse(taskJson);
    
    if (task.status !== props.status) {
      emit('task-dropped', task, props.status);
    }
  }
};

const onTaskClick = (task: Task) => {
  emit('task-click', task);
};
</script>

<style scoped>
.task-column {
  background-color: #f7f9fa;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
  display: flex;
  flex-direction: column;
  height: 100%;
}

.column-header {
  border-bottom: 1px solid var(--surface-border);
  background-color: rgba(0, 0, 0, 0.03);
  border-radius: 8px 8px 0 0;
}

.task-list {
  flex: 1;
  overflow-y: auto;
  min-height: 200px;
}

.empty-column {
  height: 150px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  border: 2px dashed var(--surface-border);
  border-radius: 6px;
  margin: 0.5rem;
}
</style>
