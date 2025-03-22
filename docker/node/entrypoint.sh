#!/bin/bash

cd /app

# Check if we need to set up the Vue.js project
if [ ! -f "package.json" ]; then
  echo "No package.json found. Setting up Vue.js project using manual approach..."
  
  # Use our manual Vue.js setup script (more reliable than interactive CLI)
  if [ -f "/usr/local/bin/manual-vue-setup.js" ]; then
    node /usr/local/bin/manual-vue-setup.js
  else
    echo "Manual setup script not found. Copying from the docker context..."
    cp /tmp/manual-vue-setup.js .
    node manual-vue-setup.js
  fi
fi

# Install any additional dependencies if needed
if [ -f "package.json" ] && [ ! -d "node_modules" ]; then
  echo "Installing dependencies..."
  npm install
fi

# Start the development server
echo "Starting Vite development server..."
exec npm run dev
