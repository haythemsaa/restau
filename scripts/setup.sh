#!/bin/bash

# RestauBoost Local Setup Script
# Usage: ./scripts/setup.sh

set -e

echo "🏗️  RestauBoost Local Setup"
echo "=========================="
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

# Check prerequisites
print_info "Checking prerequisites..."

command -v php >/dev/null 2>&1 || { print_error "PHP is not installed. Aborting."; exit 1; }
command -v composer >/dev/null 2>&1 || { print_error "Composer is not installed. Aborting."; exit 1; }
command -v npm >/dev/null 2>&1 || { print_error "NPM is not installed. Aborting."; exit 1; }
command -v psql >/dev/null 2>&1 || { print_error "PostgreSQL is not installed. Aborting."; exit 1; }

print_success "All prerequisites met"

# Copy .env file
if [ ! -f .env ]; then
    print_info "Creating .env file..."
    cp .env.example .env
    print_success ".env file created"
else
    print_info ".env file already exists"
fi

# Generate app key
print_info "Generating application key..."
php artisan key:generate
print_success "Application key generated"

# Install dependencies
print_info "Installing Composer dependencies..."
composer install
print_success "Composer dependencies installed"

print_info "Installing NPM dependencies..."
npm install
print_success "NPM dependencies installed"

# Database setup
print_info "Setting up database..."
read -p "Do you want to create the database? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    read -p "Enter database name [restauboost]: " DB_NAME
    DB_NAME=${DB_NAME:-restauboost}

    read -p "Enter database user [postgres]: " DB_USER
    DB_USER=${DB_USER:-postgres}

    read -sp "Enter database password: " DB_PASS
    echo

    # Create database
    PGPASSWORD=$DB_PASS psql -U $DB_USER -h localhost -c "CREATE DATABASE $DB_NAME;" 2>/dev/null || print_info "Database may already exist"

    # Update .env file
    sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
    sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASS/" .env

    print_success "Database configured"
fi

# Run migrations
print_info "Running database migrations..."
php artisan migrate
print_success "Migrations completed"

# Seed database
read -p "Do you want to seed the database with demo data? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    print_info "Seeding database..."
    php artisan db:seed
    print_success "Database seeded"
fi

# Link storage
print_info "Creating storage link..."
php artisan storage:link
print_success "Storage linked"

# Build assets
print_info "Building frontend assets..."
npm run dev &
print_success "Assets building in background"

# Setup Redis
print_info "Checking Redis connection..."
php artisan queue:failed-table 2>/dev/null || print_info "Queue tables already exist"
php artisan migrate 2>/dev/null || true

echo ""
echo "🎉 Setup completed successfully!"
echo ""
echo "Next steps:"
echo "  1. Update your .env file with:"
echo "     - OpenAI API key (OPENAI_API_KEY)"
echo "     - Mail configuration"
echo "     - Other services as needed"
echo ""
echo "  2. Start the development server:"
echo "     php artisan serve"
echo ""
echo "  3. Start the queue worker:"
echo "     php artisan queue:work"
echo ""
echo "  4. Start Vite dev server (for hot reload):"
echo "     npm run dev"
echo ""
echo "  5. Access the application at:"
echo "     http://localhost:8000"
echo ""
echo "  Demo credentials:"
echo "     Email: demo@restauboost.com"
echo "     Password: password"
echo ""
