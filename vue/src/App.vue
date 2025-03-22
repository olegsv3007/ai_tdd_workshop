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
:root {
  /* Apply Aura theme default font */
  --font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
}

html, body {
  margin: 0;
  padding: 0;
  height: 100%;
}

.app-container {
  font-family: var(--font-family);
  background-color: var(--p-surface-ground);
  min-height: 100vh;
  padding: 1rem;
}
</style>
