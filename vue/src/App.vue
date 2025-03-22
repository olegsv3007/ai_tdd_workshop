<template>
  <div class="app-container">
    <Toast position="bottom-right" />
    <TaskBoard />
  </div>
</template>

<script setup lang="ts">
import TaskBoard from './components/TaskBoard.vue';
import Toast from 'primevue/toast';
</script>

<style>
html, body {
  margin: 0;
  padding: 0;
  height: 100%;
  font-family: var(--font-family);
}

.app-container {
  font-family: var(--font-family);
  background-color: var(--p-surface-ground);
  min-height: 100vh;
  padding: 1rem;
}

/* Apply font to PrimeVue components */
:deep(.p-component) {
  font-family: var(--font-family);
}

/* Ensure dialog components use the correct font */
:deep(.p-dialog .p-dialog-header),
:deep(.p-dialog .p-dialog-content),
:deep(.p-dialog .p-dialog-footer) {
  font-family: var(--font-family);
}
</style>
