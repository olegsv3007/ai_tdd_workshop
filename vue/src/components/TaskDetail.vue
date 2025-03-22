<template>
  <ConfirmDialog></ConfirmDialog>
  <Dialog 
    :visible="visible" 
    :header="getDialogHeader()" 
    :modal="true"
    :closable="true"
    :dismissableMask="true"
    :style="{ width: '50vw' }"
    :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    @update:visible="(value) => emit('update:visible', value)"
    @hide="closeDialog"
  >
    <div class="task-detail">
      <div v-if="loading" class="flex justify-content-center p-4">
        <ProgressSpinner />
      </div>
      <div v-else-if="isCreating || isEditing">
        <!-- Create/Edit Mode -->
        <div class="task-edit-mode">
          <div class="p-fluid">
            <div class="grid mb-4">
              <div class="col-12 md:col-6">
                <div class="field mb-3">
                  <label for="title" class="block mb-1">Title</label>
                  <InputText 
                    id="title" 
                    v-model="editedTask.title" 
                    :class="{'p-invalid': titleError}"
                    aria-describedby="title-error"
                  />
                  <small id="title-error" class="p-error block" v-if="titleError">
                    Title is required
                  </small>
                </div>
                
                <div class="grid">
                  <div class="col-6 field mb-3">
                    <label for="type" class="block mb-1">Type</label>
                    <Dropdown
                      id="type"
                      v-model="editedTask.type"
                      :options="taskTypes"
                      optionLabel=""
                      placeholder="Select Type"
                      class="w-full"
                    />
                  </div>
                  
                  <div class="col-6 field mb-3">
                    <label for="priority" class="block mb-1">Priority</label>
                    <Dropdown
                      id="priority"
                      v-model="editedTask.priority"
                      :options="taskPriorities"
                      optionLabel=""
                      placeholder="Select Priority"
                      class="w-full"
                    />
                  </div>
                </div>
                
                <div class="grid">
                  <div class="col-6 field mb-3">
                    <label for="status" class="block mb-1">Status</label>
                    <Dropdown
                      id="status"
                      v-model="editedTask.status"
                      :options="taskStatuses"
                      optionLabel=""
                      placeholder="Select Status"
                      class="w-full"
                    />
                  </div>
                  
                  <div class="col-6 field mb-3">
                    <label for="estimatedHours" class="block mb-1">Estimated Hours</label>
                    <InputNumber
                      id="estimatedHours"
                      v-model="editedTask.estimatedHours"
                      :min="0"
                      :max="1000"
                      showButtons
                      class="w-full"
                    />
                  </div>
                </div>
                
                <div class="field mb-3">
                  <label for="assignee" class="block mb-1">Assignee</label>
                  <InputText id="assignee" v-model="editedTask.assignee" class="w-full" />
                </div>
              </div>
              
              <div class="col-12 md:col-6">
                <div class="field mb-3">
                  <label for="description" class="block mb-1">Description</label>
                  <Textarea
                    id="description"
                    v-model="editedTask.description"
                    rows="5"
                    class="w-full"
                    autoResize
                  />
                </div>
                
                <div class="field mb-3">
                  <label for="tags" class="block mb-1">Tags (comma separated)</label>
                  <Chips
                    id="tags"
                    v-model="editedTask.tags"
                    separator=","
                    placeholder="Add tag and press enter"
                    class="w-full"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else-if="task">
        <!-- View Mode -->
        <!-- View Mode -->
        <div class="task-view-mode">
          <div class="grid mb-4">
            <div class="col-12 md:col-6">
              <div class="task-metadata flex align-items-center gap-2 mb-3">
                <Tag :value="task.type" :severity="getTypeSeverity(task.type)" />
                <Tag :value="task.priority" :severity="getPrioritySeverity(task.priority)" />
                <Tag :value="task.status" severity="info" />
              </div>
              
              <h2 class="task-title text-xl font-bold mb-2">{{ task.title }}</h2>
              
              <div class="task-creators grid mb-3">
                <div class="col-6">
                  <div class="flex align-items-center">
                    <Avatar icon="pi pi-user" size="small" class="mr-2" />
                    <div>
                      <div class="text-xs text-color-secondary">Assignee</div>
                      <div>{{ task.assignee || 'Unassigned' }}</div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="flex align-items-center">
                    <Avatar icon="pi pi-user-edit" size="small" class="mr-2" />
                    <div>
                      <div class="text-xs text-color-secondary">Reporter</div>
                      <div>{{ task.reporter }}</div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="task-dates grid mb-3">
                <div class="col-6">
                  <div class="text-xs text-color-secondary">Created</div>
                  <div>{{ formatDate(task.createdAt) }}</div>
                </div>
                <div class="col-6">
                  <div class="text-xs text-color-secondary">Updated</div>
                  <div>{{ formatDate(task.updatedAt) }}</div>
                </div>
              </div>
              
              <div class="text-xs text-color-secondary mb-1">Estimated Hours</div>
              <div class="mb-4">{{ task.estimatedHours ? `${task.estimatedHours}h` : 'No estimate' }}</div>
            </div>
            
            <div class="col-12 md:col-6">
              <div class="text-xs text-color-secondary mb-1">Description</div>
              <p class="task-description mb-4 p-2 border-1 border-round surface-ground" style="min-height: 150px">
                {{ task.description }}
              </p>
              
              <div v-if="task.tags && task.tags.length > 0" class="task-tags mt-3">
                <div class="text-xs text-color-secondary mb-1">Tags</div>
                <div class="flex flex-wrap gap-1">
                  <Tag
                    v-for="tag in task.tags"
                    :key="tag"
                    :value="tag"
                    severity="secondary"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
        

      </div>
    </div>
    
    <template #footer>
      <div class="flex justify-content-between">
        <div>
          <Button
            v-if="task && !isEditing && !isCreating"
            label="Edit Task"
            icon="pi pi-pencil"
            severity="secondary"
            @click="startEditing"
            class="mr-2"
          />
          <Button
            v-if="task && !isEditing && !isCreating"
            label="Delete"
            icon="pi pi-trash"
            severity="danger"
            @click="confirmDelete"
          />
          <Button
            v-if="isEditing && !isCreating"
            label="Cancel"
            icon="pi pi-times"
            severity="secondary"
            @click="cancelEditing"
            class="mr-2"
          />
        </div>
        <div>
          <Button
            v-if="isEditing && !isCreating"
            label="Save"
            icon="pi pi-save"
            severity="success"
            @click="saveTask"
            :loading="saving"
          />
          <Button
            v-if="isCreating"
            label="Create"
            icon="pi pi-plus"
            severity="success"
            @click="createTask"
            :loading="saving"
          />
          <Button
            label="Close"
            icon="pi pi-times"
            severity="secondary"
            text
            @click="closeDialog"
          />
        </div>
      </div>
    </template>
  </Dialog>
</template>

<script setup lang="ts">
import { ref, computed, watch, defineProps, defineEmits } from 'vue';
import { Task, TaskStatus, TaskType, TaskPriority } from '../types/Task';
import { taskApi } from '../api/taskApi';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Textarea from 'primevue/textarea';
import Chips from 'primevue/chips';
import ProgressSpinner from 'primevue/progressspinner';
import Tag from 'primevue/tag';
import Avatar from 'primevue/avatar';
import ConfirmDialog from 'primevue/confirmdialog';

const props = defineProps<{
  visible: boolean;
  task: Task | null;
  isCreating?: boolean;
}>();

const emit = defineEmits<{
  'update:visible': [value: boolean];
  'task-updated': [updatedTask: Task];
  'task-created': [newTask: Task];
  'task-deleted': [taskId: number];
}>();

const toast = useToast();
const confirm = useConfirm();
const loading = ref(false);
const saving = ref(false);
const isEditing = ref(false);
const titleError = ref(false);

// Task data for editing
const editedTask = ref<Partial<Task>>({});

// Lists for dropdowns
const taskTypes: TaskType[] = ['Bug', 'Feature', 'Epic', 'Story', 'Task'];
const taskPriorities: TaskPriority[] = ['Low', 'Medium', 'High', 'Critical'];
const taskStatuses: TaskStatus[] = ['To Do', 'In Progress', 'Review', 'Done'];

// Set default values for new task
const getDefaultTask = (): Partial<Task> => {
  return {
    title: '',
    description: '',
    status: 'To Do',
    type: 'Task',
    priority: 'Medium',
    assignee: null,
    tags: []
  };
};

// Reset form when task or isCreating changes
watch([() => props.task, () => props.isCreating], ([newTask, newIsCreating]) => {
  if (newIsCreating) {
    // Creating a new task
    editedTask.value = getDefaultTask();
    isEditing.value = true;
    titleError.value = false;
  } else if (newTask) {
    // Editing an existing task
    editedTask.value = { ...newTask };
    isEditing.value = false;
    titleError.value = false;
  }
}, { immediate: true });

// Get the dialog header based on current state
const getDialogHeader = (): string => {
  if (props.isCreating) return 'Create New Task';
  if (!props.task) return '';
  return `Task #${props.task.id}: ${isEditing.value ? 'Edit' : 'View'} Details`;
};

// Start editing mode
const startEditing = () => {
  isEditing.value = true;
};

// Cancel editing and revert changes
const cancelEditing = () => {
  if (props.task) {
    editedTask.value = { ...props.task };
  }
  titleError.value = false;
  isEditing.value = false;
};

// Close the dialog
const closeDialog = () => {
  isEditing.value = false;
  emit('update:visible', false);
};

// Save the edited task
const saveTask = async () => {
  if (!editedTask.value.title?.trim()) {
    titleError.value = true;
    return;
  }

  if (!props.task?.id) return;
  
  saving.value = true;
  try {
    const updatedTask = await taskApi.updateTask(props.task.id, editedTask.value);
    
    toast.add({
      severity: 'success',
      summary: 'Task Updated',
      detail: `Task #${updatedTask.id} was updated successfully`,
      life: 3000
    });
    
    emit('task-updated', updatedTask);
    isEditing.value = false;
  } catch (error) {
    console.error('Error updating task:', error);
    
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Failed to update task',
      life: 3000
    });
  } finally {
    saving.value = false;
  }
};

// Create a new task
const createTask = async () => {
  if (!editedTask.value.title?.trim()) {
    titleError.value = true;
    return;
  }
  
  saving.value = true;
  try {
    const newTask = await taskApi.createTask(editedTask.value);
    
    emit('task-created', newTask);
    closeDialog();
  } catch (error) {
    console.error('Error creating task:', error);
    
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Failed to create task',
      life: 3000
    });
  } finally {
    saving.value = false;
  }
};

// Confirm task deletion
const confirmDelete = () => {
  if (!props.task) return;
  
  confirm.require({
    message: `Are you sure you want to delete task #${props.task.id}: "${props.task.title}"?`,
    header: 'Delete Confirmation',
    icon: 'pi pi-exclamation-triangle',
    acceptClass: 'p-button-danger',
    accept: deleteTask,
    reject: () => {}
  });
};

// Delete the task
const deleteTask = async () => {
  if (!props.task?.id) return;
  
  saving.value = true;
  try {
    await taskApi.deleteTask(props.task.id);
    
    toast.add({
      severity: 'success',
      summary: 'Task Deleted',
      detail: `Task #${props.task.id} has been deleted successfully`,
      life: 3000
    });
    
    emit('task-deleted', props.task.id);
    closeDialog();
  } catch (error) {
    console.error('Error deleting task:', error);
    
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Failed to delete task',
      life: 3000
    });
  } finally {
    saving.value = false;
  }
};

// Format date for display
const formatDate = (dateString: string): string => {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
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

// Get UI severity for task priority
const getPrioritySeverity = (priority: TaskPriority): string => {
  const severities: Record<TaskPriority, string> = {
    'Low': 'secondary',
    'Medium': 'info',
    'High': 'warning',
    'Critical': 'danger'
  };
  return severities[priority] || 'secondary';
};
</script>

<style scoped>
.task-detail {
  max-height: 70vh;
  overflow-y: auto;
}

.task-description {
  white-space: pre-line;
}
</style>
