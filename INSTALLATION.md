# Web Desa Burujul Jaya - Installation Guide

## Requirements
- PHP 8.3+
- Composer
- Node.js & NPM
- MySQL / MariaDB (or SQLite for development)
- Redis (Optional, for caching and queues)

## Local Development (Laragon / Herd / Valet)
1. **Clone Repository**
   ```bash
   git clone <repo-url>
   cd burujulJaya
   ```
2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```
3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Update `.env` with your local database credentials.

4. **Database & Seeding**
   ```bash
   php artisan migrate:fresh --seed
   ```
5. **Storage Link**
   ```bash
   php artisan storage:link
   ```
6. **Compile Assets & Run**
   ```bash
   npm run dev
   ```

## Production Docker Installation
*Note: A generic `docker-compose.yml` is provided for standard deployments.*
1. **Prepare Environment**
   Ensure Docker and Docker Compose are installed.
2. **Configure Env**
   ```bash
   cp .env.example .env
   # Set DB_CONNECTION to your prefered engine in .env
   ```
3. **Build & Up**
   ```bash
   docker-compose up -d --build
   ```
4. **Initialize Container**
   ```bash
   docker-compose exec app composer install --optimize-autoloader --no-dev
   docker-compose exec app php artisan migrate --force
   docker-compose exec app php artisan storage:link
   docker-compose exec app npm run build
   ```
