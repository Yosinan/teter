#!/bin/bash

# PostgreSQL backup (using modern pg_dump format)
docker compose exec -T db pg_dump -U laravel -Fc laravel > /backups/db_$(date +\%F).pgdump

# Redis RDB snapshot
docker compose exec -T redis redis-cli save

# Application data
tar -czvf /backups/app_$(date +\%F).tar.gz \
  /home/deployer/apps/laravel/storage \
  /home/deployer/traefik/acme.json

# Cleanup (keep 7 days)
find /backups -type f -mtime +7 -delete

# Notify user
echo "Backup completed successfully! Files saved in /backups:"
ls -lh /backups

