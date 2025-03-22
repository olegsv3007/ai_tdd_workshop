<template>
  <div class="card flex justify-content-center">
    <div class="flex flex-column gap-2 align-items-center">
      <h1>Vue.js 3 with TypeScript, Vite, and PrimeVue 4</h1>
      <Button label="Welcome to Your Vue.js App" icon="pi pi-check" />
    </div>
  </div>
</template>

<script setup lang="ts">
import Button from "primevue/button";
</script>

<style scoped>
.card {
  padding: 2em;
  text-align: center;
}
h1 {
  font-size: 1.8em;
  margin-bottom: 1em;
}
</style>
