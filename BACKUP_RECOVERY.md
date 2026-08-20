# Backup & Recovery Guide

## Automated Backups
The system is equipped with a `BackupService` accessible via the Admin Dashboard (`System Management` -> `Backups`).
Administrators with the `manage backups` permission can generate and download backups of the database and uploaded storage.

## Manual Recovery
If the server crashes and you need to restore from a backup file:

### Database (SQLite)
If you are using SQLite, simply replace the `database/database.sqlite` file with the downloaded backup.

### Database (MySQL)
If you are using MySQL, import the `.sql` backup file using:
```bash
mysql -u username -p database_name < backup_file.sql
```

### Storage
Extract the downloaded storage backup zip into `storage/app/public/`. Ensure the web server user (`www-data` or `nginx`) has ownership:
```bash
unzip storage_backup.zip -d storage/app/public/
chown -R www-data:www-data storage/app/public/
```
