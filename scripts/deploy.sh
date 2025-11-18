#!/bin/bash

# RestauBoost Deployment Script
# Usage: ./scripts/deploy.sh [environment]
# Example: ./scripts/deploy.sh production

set -e

ENVIRONMENT=${1:-production}

echo "🚀 Starting deployment for environment: $ENVIRONMENT"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

# Check if .env file exists
if [ ! -f .env ]; then
    print_error ".env file not found!"
    echo "Please create .env file from .env.example"
    exit 1
fi

print_info "Step 1: Pulling latest code..."
git pull origin main
print_success "Code updated"

print_info "Step 2: Installing composer dependencies..."
composer install --no-dev --optimize-autoloader
print_success "Composer dependencies installed"

print_info "Step 3: Installing npm dependencies..."
npm ci --production
print_success "NPM dependencies installed"

print_info "Step 4: Running database migrations..."
php artisan migrate --force
print_success "Migrations completed"

print_info "Step 5: Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
print_success "Caches cleared"

print_info "Step 6: Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
print_success "Application optimized"

print_info "Step 7: Building frontend assets..."
npm run build
print_success "Assets built"

print_info "Step 8: Setting permissions..."
chmod -R 755 storage bootstrap/cache
print_success "Permissions set"

print_info "Step 9: Restarting queue workers..."
php artisan queue:restart
print_success "Queue workers restarted"

print_info "Step 10: Running health checks..."
php artisan route:list > /dev/null 2>&1
if [ $? -eq 0 ]; then
    print_success "Health check passed"
else
    print_error "Health check failed!"
    exit 1
fi

echo ""
print_success "🎉 Deployment completed successfully!"
echo ""
echo "Next steps:"
echo "  - Monitor application logs"
echo "  - Check queue workers: php artisan queue:work"
echo "  - Verify application at: $APP_URL"
