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
        <div class="task-edit-mode p-1">
          <div class="p-fluid">
            <div class="form-header mb-2 pb-1 border-bottom-1 border-300">
              <h2 class="text-lg font-medium m-0 p-0">{{ isCreating ? 'Create New Task' : 'Edit Task' }}</h2>
            </div>

            <!-- Status indicators bar - Simple dropdowns like in screenshot -->
            <div class="flex flex-wrap mb-2 gap-1">
              <Dropdown
                v-model="editedTask.type"
                :options="taskTypes"
                placeholder="Type"
                class="w-10rem mr-1"
              />
              
              <Dropdown
                v-model="editedTask.priority"
                :options="taskPriorities"
                placeholder="Priority"
                class="w-10rem mr-1"
              />
              
              <Dropdown
                v-model="editedTask.status"
                :options="taskStatuses"
                placeholder="Status"
                class="w-10rem"
              />
            </div>
            
            <!-- Title field - simple with no background -->
            <div class="mb-2">
              <InputText 
                id="title" 
                v-model="editedTask.title" 
                placeholder="Enter task title"
                :class="['w-full text-xl', {'p-invalid': titleError}]"
                aria-describedby="title-error"
              />
              <small id="title-error" class="p-error block mt-1" v-if="titleError">
                <i class="pi pi-exclamation-circle mr-1"></i>Title is required
              </small>
            </div>

            <!-- Assignee box -->
            <div class="mb-3">
              <div class="p-1 surface-50 border-round mb-2">
                <div class="flex align-items-center mb-1">
                  <i class="pi pi-user text-blue-600 mr-1"></i>
                  <span class="font-medium">Assignee</span>
                </div>
                <InputText 
                  v-model="editedTask.assignee" 
                  placeholder="Enter assignee name" 
                  class="w-full" 
                />
              </div>
              
              <!-- Reporter box -->
              <div class="p-1 surface-50 border-round mb-2">
                <div class="flex align-items-center mb-1">
                  <i class="pi pi-user-edit text-green-600 mr-1"></i>
                  <span class="font-medium">Reporter</span>
                </div>
                <InputText 
                  v-model="editedTask.reporter" 
                  placeholder="Enter reporter name" 
                  class="w-full" 
                />
              </div>
              
              <!-- Estimated Hours box -->
              <div class="p-1 surface-50 border-round mb-2">
                <div class="flex align-items-center mb-1">
                  <i class="pi pi-clock text-purple-600 mr-1"></i>
                  <span class="font-medium">Estimated Hours</span>
                </div>
                <InputNumber
                  v-model="editedTask.estimatedHours"
                  :min="0"
                  placeholder="Hours"
                  class="w-full"
                  :showButtons="false"
                />
              </div>
              
              <!-- Description box -->
              <div class="p-1 surface-50 border-round mb-2">
                <div class="flex align-items-center mb-1">
                  <i class="pi pi-file-edit text-blue-600 mr-1"></i>
                  <span class="font-medium">Description</span>
                </div>
                <Textarea
                  v-model="editedTask.description"
                  rows="4"
                  class="w-full text-md"
                  placeholder="Enter task description"
                  autoResize
                />
              </div>
              
              <!-- Tags box -->
              <div class="p-1 surface-50 border-round">
                <div class="flex align-items-center mb-1">
                  <i class="pi pi-tags text-orange-600 mr-1"></i>
                  <span class="font-medium">Tags</span>
                </div>
                <Chips
                  v-model="editedTaskTagNames"
                  separator=","
                  placeholder="Add tag and press enter"
                  class="w-full"
                />
                <small class="block mt-2 text-color-secondary">
                  <i class="pi pi-info-circle mr-1"></i>Type a tag and press Enter or comma to add
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else-if="task">
        <!-- View Mode -->
        <div class="task-view-mode p-1">
          <div class="view-header mb-2 pb-1 border-bottom-1 border-300">
            <h2 class="text-lg font-medium m-0 p-0">Task Details</h2>
          </div>
          
          <div class="task-metadata flex align-items-center gap-1 mb-2">
            <Tag :value="task.type" :severity="getTypeSeverity(task.type)" class="font-medium" />
            <Tag :value="task.priority" :severity="getPrioritySeverity(task.priority)" 
              :class="{'high-priority-tag': task.priority === 'High'}" class="font-medium" />
            <Tag :value="task.status" severity="info" class="font-medium" />
          </div>
          
          <h2 class="task-title text-xl font-bold mb-4 p-2 surface-100 border-round">{{ task.title }}</h2>
          
          <div class="grid mb-4">
            <div class="col-12 md:col-6">
              <div class="task-creators grid">
                <div class="col-12 mb-3">
                  <div class="flex align-items-center p-1 surface-50 border-round">
                    <Avatar icon="pi pi-user" style="background-color:var(--p-blue-100);color:var(--p-blue-700)" size="large" class="mr-2" />
                    <div>
                      <div class="text-md font-medium">Assignee</div>
                      <div class="text-lg text-600">{{ task.assignee || 'Unassigned' }}</div>
                    </div>
                  </div>
                </div>
                
                <div class="col-12">
                  <div class="flex align-items-center p-1 surface-50 border-round">
                    <Avatar icon="pi pi-user-edit" style="background-color:var(--p-green-100);color:var(--p-green-700)" size="large" class="mr-2" />
                    <div>
                      <div class="text-md font-medium">Reporter</div>
                      <div class="text-lg text-600">{{ task.reporter }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-12 md:col-6">
              <div class="task-dates grid">
                <div class="col-12 mb-3">
                  <div class="flex align-items-center p-1 surface-50 border-round">
                    <Avatar icon="pi pi-calendar-plus" style="background-color:var(--p-orange-100);color:var(--p-orange-700)" size="large" class="mr-2" />
                    <div>
                      <div class="text-md font-medium">Created</div>
                      <div class="text-lg text-600">{{ formatDate(task.createdAt) }}</div>
                    </div>
                  </div>
                </div>
                
                <div class="col-12">
                  <div class="flex align-items-center p-1 surface-50 border-round">
                    <Avatar icon="pi pi-calendar" style="background-color:var(--p-purple-100);color:var(--p-purple-700)" size="large" class="mr-2" />
                    <div>
                      <div class="text-md font-medium">Updated</div>
                      <div class="text-lg text-600">{{ formatDate(task.updatedAt) }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="task-description mb-2 p-2 surface-50 border-round">
            <div class="text-lg font-medium mb-2 flex align-items-center">
              <i class="pi pi-file-edit mr-2" style="color:var(--p-blue-600)"></i> Description
            </div>
            <p class="text-md line-height-3 whitespace-pre-line p-2">{{ task.description || 'No description provided for this task.' }}</p>
          </div>
          
          <div class="task-estimated-hours mb-2 p-2 surface-50 border-round">
            <div class="text-lg font-medium mb-2 flex align-items-center">
              <i class="pi pi-stopwatch mr-2" style="color:var(--p-orange-600)"></i> Estimated Hours
            </div>
            <p class="text-lg text-600 p-2">{{ task.estimatedHours ? `${task.estimatedHours}h` : 'No estimate provided' }}</p>
          </div>
          
          <div v-if="task.tags && task.tags.length > 0" class="task-tags mb-2 p-2 surface-50 border-round">
            <div class="text-lg font-medium mb-2 flex align-items-center">
              <i class="pi pi-tags mr-2" style="color:var(--p-green-600)"></i> Tags
            </div>
            <div class="flex flex-wrap gap-2 p-2">
              <Tag
                v-for="tag in task.tags"
                :key="tag.name"
                :value="tag.name"
                severity="secondary"
              />
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
    emit('task-updated', editedTask.value);

    toast.add({
      severity: 'success',
      summary: 'Task Updated',
      detail: `Task #${editedTask.id} was updated successfully`,
      life: 3000
    });
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
    emit('task-created', editedTask.value);
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
  if (!props.task.id) return;
  
  confirm.require({
    message: `Are you sure you want to delete task #${props.task.id}: "${props.task.title}"?`,
    header: 'Delete Confirmation',
    icon: 'pi pi-exclamation-triangle',
    acceptClass: 'p-button-danger',
    accept: () => emit('task-deleted', props.task.id),
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

const editedTaskTagNames = computed({
  get: () => {
    return editedTask.value.tags?.map(tag =>
        typeof tag === 'string' ? tag : tag.name
    ) || [];
  },
  set: (newTags) => {
    editedTask.value.tags = newTags;
  }
});
</script>

<style scoped>
.task-detail {
  max-height: 80vh;
  overflow-y: auto;
  overflow-x: hidden;
}

.task-description {
  white-space: pre-line;
}

.task-description p {
  font-size: 1.1rem;
  color: var(--text-color);
}
</style>
