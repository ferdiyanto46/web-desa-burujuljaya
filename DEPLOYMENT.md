# Web Desa Burujul Jaya - Deployment Guide

## Continuous Deployment (Laravel Forge / CI/CD)

For automated deployments, ensure the following commands run in sequence after pulling the latest code:

### 1. Install Dependencies
```bash
composer install --no-interaction --prefer-dist --optimize-autoloader
npm ci
```

### 2. Build Assets
```bash
npm run build
```

### 3. Clear and Cache System
```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 4. Database Migrations
```bash
php artisan migrate --force
```

### 5. Restart Queues
```bash
php artisan queue:restart
```

## Zero-Downtime Deployments
If using Envoyer or similar symlink-based deployment tools, remember to configure the `.env` file and `storage` directory as shared/persistent resources.

## Scheduled Tasks (Cron Jobs)
Ensure the Laravel scheduler is running. Add this to your production server's crontab:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```
