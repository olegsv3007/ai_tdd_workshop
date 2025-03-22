FROM node:latest

WORKDIR /app

# Install Vue CLI globally
RUN npm install -g @vue/cli

# Install basic dependencies for Vue.js with Vite
COPY ./package.json ./
RUN npm install

# Configure Vite for proper host binding
COPY ./vite.config.js ./
COPY ./index.html ./
COPY ./tsconfig.json ./

# Set up application source code
COPY ./src ./src

EXPOSE 8080

# Start the development server
CMD ["npm", "run", "dev"]
