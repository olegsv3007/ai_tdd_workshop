<template>
  <div 
    :class="['task-card', priorityBorderClass]"
    draggable="true"
    @dragstart="$emit('dragstart', $event, task)"
    @click="$emit('click', task)"
  >
    <div class="task-card-header flex align-items-center justify-content-between mb-2">
      <div class="flex align-items-center">
        <Badge :value="task.id" severity="info" class="mr-2" />
        <Tag :value="task.type" :severity="getTypeSeverity(task.type)" />
      </div>
      <Tag :value="task.priority" :severity="getPrioritySeverity(task.priority)" :class="{'high-priority-tag': task.priority === 'High'}" style="font-weight: bold;" />
    </div>
    
    <h3 class="task-title text-base font-bold mb-2">{{ task.title }}</h3>
    
    <p class="task-description text-sm text-color-secondary mb-3">
      {{ shortenDescription(task.description) }}
    </p>
    
    <div class="task-meta grid">
      <div class="col-6 flex align-items-center">
        <i class="pi pi-user mr-1" style="font-size: 0.75rem"></i>
        <span class="text-xs">{{ task.assignee || 'Unassigned' }}</span>
      </div>
      <div class="col-6 flex align-items-center justify-content-end">
        <i class="pi pi-clock mr-1" style="font-size: 0.75rem"></i>
        <span class="text-xs">{{ task.estimatedHours ? `${task.estimatedHours}h` : 'No estimate' }}</span>
      </div>
    </div>
    
    <div v-if="task.tags && task.tags.length > 0" class="task-tags flex flex-wrap gap-1 mt-2">
      <Tag
        v-for="tag in task.tags"
        :key="tag.id"
        :value="tag.name"
        severity="secondary"
        class="text-xs"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps, defineEmits, computed } from 'vue';
import { Task, TaskType, TaskPriority } from '../types/Task';

const props = defineProps<{
  task: Task;
}>();

// Get the CSS class for the priority border
const priorityBorderClass = computed(() => {
  switch (props.task.priority) {
    case 'Critical':
      return 'priority-critical';
    case 'High':
      return 'priority-high';
    case 'Medium':
      return 'priority-medium';
    case 'Low':
      return 'priority-low';
    default:
      return 'priority-medium';
  }
});

defineEmits<{
  dragstart: [event: DragEvent, task: Task];
  click: [task: Task];
}>();

// Shorten description for display
const shortenDescription = (description: string, maxLength = 100): string => {
  if (description.length <= maxLength) return description;
  return description.substring(0, maxLength) + '...';
};

// Get UI severity for task type
const getTypeSeverity = (type: TaskType): string => {
  const severities: Record<TaskType, string> = {
    'Bug': 'danger',
    'Feature': 'success',
    'Epic': 'warning',
    'Story': 'info',
    'Task': 'secondary'
  };
  return severities[type] || 'secondary';
};

// Get UI severity for task priority (matching border colors)
const getPrioritySeverity = (priority: TaskPriority): string => {
  const severities: Record<TaskPriority, string> = {
    'Low': 'success',     // Green to match --p-green-500 border
    'Medium': 'info',     // Blue to match --p-blue-500 border
    'High': 'warning',    // Orange to match --p-orange-500 border
    'Critical': 'danger'  // Red to match --p-red-600 border
  };
  return severities[priority] || 'info';
};
</script>

<style scoped>
/* Using PrimeVue theme colors for better theme compatibility */
.task-card {
  background-color: white;
  border-radius: 8px;
  padding: 1rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
  cursor: pointer;
  transition: all 0.3s cubic-bezier(.25,.8,.25,1);
  border-left: 6px solid transparent;
}

/* Priority-based left borders using PrimeVue primitive color tokens */
.priority-critical {
  border-left-color: var(--p-red-100);
}

.priority-high {
  border-left-color: var(--p-orange-100);
}

.priority-medium {
  border-left-color: var(--p-blue-100);
}

.priority-low {
  border-left-color: var(--p-green-100);
}

.task-card:hover {
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
  transform: translateY(-2px);
}

/* Custom style for high priority tag to ensure orange color */
:deep(.high-priority-tag) {
  background-color: var(--p-orange-100) !important;
  color:  var(--p-orange-700);
}

.task-card:active {
  cursor: grabbing;
}

.task-card[draggable="true"] {
  cursor: grab;
}

.task-title {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
}

.task-description {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  color: var(--text-color-secondary);
}

.task-tags :deep(.p-tag) {
  padding: 0.15rem 0.4rem;
  font-size: 0.7rem;
}
</style>
