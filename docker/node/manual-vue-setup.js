const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const APP_DIR = '/app';

// Create essential files for a Vue 3 + TS + Vite project manually
const files = {
  'package.json': `{
  "name": "vue-app",
  "private": true,
  "version": "0.0.0",
  "type": "module",
  "scripts": {
    "dev": "vite",
    "build": "vue-tsc && vite build",
    "preview": "vite preview"
  },
  "dependencies": {
    "primevue": "^4.0.0",
    "primeicons": "^6.0.1",
    "vue": "^3.3.8"
  },
  "devDependencies": {
    "@vitejs/plugin-vue": "^4.5.0",
    "typescript": "^5.2.2",
    "vite": "^5.0.0",
    "vue-tsc": "^1.8.22"
  }
}`,
  'vite.config.ts': `import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  server: {
    host: '0.0.0.0',
    port: 8080
  }
})`,
  'tsconfig.json': `{
  "compilerOptions": {
    "target": "ES2020",
    "useDefineForClassFields": true,
    "module": "ESNext",
    "lib": ["ES2020", "DOM", "DOM.Iterable"],
    "skipLibCheck": true,

    /* Bundler mode */
    "moduleResolution": "bundler",
    "allowImportingTsExtensions": true,
    "resolveJsonModule": true,
    "isolatedModules": true,
    "noEmit": true,
    "jsx": "preserve",

    /* Linting */
    "strict": true,
    "noUnusedLocals": true,
    "noUnusedParameters": true,
    "noFallthroughCasesInSwitch": true
  },
  "include": ["src/**/*.ts", "src/**/*.d.ts", "src/**/*.tsx", "src/**/*.vue"],
  "references": [{ "path": "./tsconfig.node.json" }]
}`,
  'tsconfig.node.json': `{
  "compilerOptions": {
    "composite": true,
    "skipLibCheck": true,
    "module": "ESNext",
    "moduleResolution": "bundler",
    "allowSyntheticDefaultImports": true
  },
  "include": ["vite.config.ts"]
}`,
  'index.html': `<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="/vite.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vue 3 + TS + Vite + PrimeVue</title>
  </head>
  <body>
    <div id="app"></div>
    <script type="module" src="/src/main.ts"></script>
  </body>
</html>`,
  'src/main.ts': `import { createApp } from 'vue'
import App from './App.vue'
import PrimeVue from 'primevue/config'
import 'primevue/resources/themes/aura-light-green/theme.css'
import 'primeicons/primeicons.css'

const app = createApp(App)
app.use(PrimeVue)
app.mount('#app')`,
  'src/App.vue': `<template>
  <div class="card flex justify-content-center">
    <div class="flex flex-column gap-2 align-items-center">
      <img alt="Vue logo" class="logo" src="./assets/vue.svg" width="125" height="125" />
      <h1>Vue.js 3 with TypeScript & PrimeVue 4</h1>
      <Button label="Welcome to Your Vue.js App" icon="pi pi-check" />
    </div>
  </div>
</template>

<script setup lang="ts">
import Button from 'primevue/button'
</script>

<style scoped>
.logo {
  margin: 1.5em auto;
}
h1 {
  font-size: 1.8em;
  margin-bottom: 1em;
}
.card {
  padding: 2em;
  text-align: center;
}
</style>`,
  'src/vite-env.d.ts': `/// <reference types="vite/client" />`,
  'src/assets/vue.svg': `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--logos" width="37.07" height="36" preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 198"><path fill="#41B883" d="M204.8 0H256L128 220.8L0 0h97.92L128 51.2L157.44 0h47.36Z"></path><path fill="#41B883" d="m0 0l128 220.8L256 0h-51.2L128 132.48L50.56 0H0Z"></path><path fill="#35495E" d="M50.56 0L128 133.12L204.8 0h-47.36L128 51.2L97.92 0h-47.36Z"></path></svg>`,
};

// Create directories and files
function setupVueProject() {
  console.log('Setting up Vue.js project...');
  
  // Clean directory except for install-related files
  const existingFiles = fs.readdirSync(APP_DIR);
  existingFiles.forEach(file => {
    if (file !== 'manual-vue-setup.js' && file !== 'entrypoint.sh' && file !== 'install.sh') {
      const filePath = path.join(APP_DIR, file);
      if (fs.lstatSync(filePath).isDirectory()) {
        fs.rmSync(filePath, { recursive: true, force: true });
      } else {
        fs.unlinkSync(filePath);
      }
    }
  });
  
  // Create directories
  if (!fs.existsSync(path.join(APP_DIR, 'src'))) {
    fs.mkdirSync(path.join(APP_DIR, 'src'), { recursive: true });
  }
  
  if (!fs.existsSync(path.join(APP_DIR, 'src/assets'))) {
    fs.mkdirSync(path.join(APP_DIR, 'src/assets'), { recursive: true });
  }
  
  // Write files
  Object.entries(files).forEach(([filePath, content]) => {
    const fullPath = path.join(APP_DIR, filePath);
    
    // Create parent directories if they don't exist
    const dir = path.dirname(fullPath);
    if (!fs.existsSync(dir)) {
      fs.mkdirSync(dir, { recursive: true });
    }
    
    fs.writeFileSync(fullPath, content);
    console.log(`Created ${filePath}`);
  });

  console.log('Installing dependencies...');
  try {
    execSync('npm install', { cwd: APP_DIR, stdio: 'inherit' });
    console.log('Dependencies installed successfully');
  } catch (error) {
    console.error('Error installing dependencies:', error.message);
  }
}

setupVueProject();
