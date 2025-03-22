FROM node:latest

WORKDIR /app

# Install Vue CLI globally
RUN npm install -g @vue/cli

# Copy package.json and install dependencies
COPY ./package.json ./
RUN npm install

# Copy configuration files
COPY ./vite.config.js ./
COPY ./index.html ./
COPY ./tsconfig.json ./

# Copy source files to appropriate location
# Note: The actual source files will be mounted from the host at runtime
# via the volume mapping in docker-compose.yml

EXPOSE 8080

# Start the development server
CMD ["npm", "run", "dev"]
