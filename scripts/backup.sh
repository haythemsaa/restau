#!/bin/bash

# RestauBoost Backup Script
# Usage: ./scripts/backup.sh

set -e

# Load environment variables
if [ -f .env ]; then
    export $(cat .env | grep -v '^#' | xargs)
fi

BACKUP_DIR="backups"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_NAME="restauboost_backup_${TIMESTAMP}"

echo "🔒 Starting backup..."

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
echo "📊 Backing up database..."
pg_dump -U $DB_USERNAME -h $DB_HOST -p $DB_PORT $DB_DATABASE > "${BACKUP_DIR}/${BACKUP_NAME}_database.sql"
echo "✓ Database backed up"

# Files backup
echo "📁 Backing up files..."
tar -czf "${BACKUP_DIR}/${BACKUP_NAME}_files.tar.gz" \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='storage/logs/*' \
    --exclude='backups' \
    .
echo "✓ Files backed up"

# Compress all backups
echo "🗜️  Compressing backups..."
tar -czf "${BACKUP_DIR}/${BACKUP_NAME}_complete.tar.gz" \
    "${BACKUP_DIR}/${BACKUP_NAME}_database.sql" \
    "${BACKUP_DIR}/${BACKUP_NAME}_files.tar.gz"

# Clean up individual files
rm "${BACKUP_DIR}/${BACKUP_NAME}_database.sql"
rm "${BACKUP_DIR}/${BACKUP_NAME}_files.tar.gz"

echo "✓ Backup completed: ${BACKUP_DIR}/${BACKUP_NAME}_complete.tar.gz"

# Remove backups older than 30 days
echo "🧹 Cleaning old backups..."
find $BACKUP_DIR -name "restauboost_backup_*.tar.gz" -mtime +30 -delete
echo "✓ Old backups cleaned"

echo "🎉 Backup completed successfully!"
