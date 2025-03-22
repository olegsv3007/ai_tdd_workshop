# Docker Environment with PHP 8.4, MySQL 8, Nginx, Node.js, Symfony and Vue.js

This project sets up a complete development environment with:

- PHP 8.4
- MySQL 8
- Nginx
- Node.js
- Symfony
- Vue.js 3 with TypeScript, Vite, and PrimeVue 4

## Getting Started

1. Start the Docker environment:

```bash
docker-compose up -d
```

That's it! Both Symfony and Vue.js will be automatically installed on the first run.

2. To rebuild the containers after making changes to Dockerfiles:

```bash
docker-compose up -d --build
```

## Accessing the Applications

- Symfony app: http://localhost
- Vue.js app: http://localhost:8080

## Database Connection

- Host: localhost
- Port: 3306
- Database: symfony
- Username: symfony
- Password: symfony

## Services

- PHP: PHP-FPM 8.4 for Symfony backend
- MySQL: Database server
- Nginx: Web server for Symfony
- Node.js: For Vue.js frontend

## Stopping the Environment

```bash
docker-compose down
```

To remove volumes (database data):

```bash
docker-compose down -v
```
