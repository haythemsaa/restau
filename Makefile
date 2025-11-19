.PHONY: help install start stop test clean deploy

# Colors for output
BLUE=\033[0;34m
GREEN=\033[0;32m
RED=\033[0;31m
NC=\033[0m # No Color

help: ## Show this help message
	@echo '${BLUE}RestauBoost - Available Commands${NC}'
	@echo ''
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "${GREEN}%-20s${NC} %s\n", $$1, $$2}'

install: ## Install all dependencies and setup
	@echo "${BLUE}Installing RestauBoost...${NC}"
	composer install
	npm install
	cp .env.example .env
	php artisan key:generate
	@echo "${GREEN}✓ Installation complete${NC}"
	@echo "${BLUE}Next steps:${NC}"
	@echo "  1. Configure .env file"
	@echo "  2. Run: make migrate"
	@echo "  3. Run: make start"

migrate: ## Run database migrations
	@echo "${BLUE}Running migrations...${NC}"
	php artisan migrate
	@echo "${GREEN}✓ Migrations complete${NC}"

seed: ## Seed database with demo data
	@echo "${BLUE}Seeding database...${NC}"
	php artisan db:seed
	@echo "${GREEN}✓ Database seeded${NC}"

fresh: ## Fresh migrate and seed
	@echo "${BLUE}Resetting database...${NC}"
	php artisan migrate:fresh --seed
	@echo "${GREEN}✓ Database reset complete${NC}"

start: ## Start development servers
	@echo "${BLUE}Starting servers...${NC}"
	@echo "Backend: http://localhost:8000"
	@echo "Frontend: Hot reload enabled"
	@echo "Mailpit: http://localhost:8025"
	php artisan serve & npm run dev & php artisan queue:work

stop: ## Stop all running servers
	@echo "${RED}Stopping servers...${NC}"
	@pkill -f "php artisan serve" || true
	@pkill -f "npm run dev" || true
	@pkill -f "php artisan queue:work" || true
	@echo "${GREEN}✓ Servers stopped${NC}"

test: ## Run all tests
	@echo "${BLUE}Running tests...${NC}"
	php artisan test
	@echo "${GREEN}✓ Tests complete${NC}"

test-coverage: ## Run tests with coverage
	@echo "${BLUE}Running tests with coverage...${NC}"
	php artisan test --coverage
	@echo "${GREEN}✓ Tests with coverage complete${NC}"

test-feature: ## Run feature tests only
	@echo "${BLUE}Running feature tests...${NC}"
	php artisan test --testsuite=Feature

test-unit: ## Run unit tests only
	@echo "${BLUE}Running unit tests...${NC}"
	php artisan test --testsuite=Unit

lint: ## Run code linters
	@echo "${BLUE}Linting code...${NC}"
	./vendor/bin/phpstan analyse || true
	npm run lint || true
	@echo "${GREEN}✓ Linting complete${NC}"

format: ## Format code
	@echo "${BLUE}Formatting code...${NC}"
	./vendor/bin/php-cs-fixer fix || true
	npm run format || true
	@echo "${GREEN}✓ Formatting complete${NC}"

clean: ## Clean cache and temporary files
	@echo "${BLUE}Cleaning...${NC}"
	php artisan cache:clear
	php artisan config:clear
	php artisan route:clear
	php artisan view:clear
	rm -rf bootstrap/cache/*.php
	@echo "${GREEN}✓ Clean complete${NC}"

optimize: ## Optimize for production
	@echo "${BLUE}Optimizing...${NC}"
	composer install --no-dev --optimize-autoloader
	npm run build
	php artisan config:cache
	php artisan route:cache
	php artisan view:cache
	php artisan optimize
	@echo "${GREEN}✓ Optimization complete${NC}"

deploy: ## Deploy to production
	@echo "${BLUE}Deploying...${NC}"
	./scripts/deploy.sh production
	@echo "${GREEN}✓ Deployment complete${NC}"

backup: ## Backup database and files
	@echo "${BLUE}Creating backup...${NC}"
	./scripts/backup.sh
	@echo "${GREEN}✓ Backup complete${NC}"

docker-up: ## Start Docker containers
	@echo "${BLUE}Starting Docker containers...${NC}"
	docker-compose up -d
	@echo "${GREEN}✓ Containers started${NC}"

docker-down: ## Stop Docker containers
	@echo "${RED}Stopping Docker containers...${NC}"
	docker-compose down
	@echo "${GREEN}✓ Containers stopped${NC}"

docker-build: ## Build Docker images
	@echo "${BLUE}Building Docker images...${NC}"
	docker-compose build
	@echo "${GREEN}✓ Build complete${NC}"

docker-logs: ## Show Docker logs
	docker-compose logs -f

docker-shell: ## Access app container shell
	docker-compose exec app bash

# Database commands
db-create: ## Create database
	createdb restauboost || true
	@echo "${GREEN}✓ Database created${NC}"

db-drop: ## Drop database (DANGER)
	@echo "${RED}Dropping database...${NC}"
	dropdb restauboost || true
	@echo "${GREEN}✓ Database dropped${NC}"

# Artisan commands shortcuts
serve: ## Start Laravel server only
	php artisan serve

queue: ## Start queue worker
	php artisan queue:work

queue-restart: ## Restart queue workers
	php artisan queue:restart

tinker: ## Open tinker REPL
	php artisan tinker

# Frontend commands
build: ## Build frontend assets
	npm run build

dev: ## Start frontend dev server
	npm run dev

watch: ## Watch frontend assets
	npm run watch

# Mobile commands
mobile-install: ## Install mobile dependencies
	cd mobile && npm install

mobile-ios: ## Run iOS app
	cd mobile && npm run ios

mobile-android: ## Run Android app
	cd mobile && npm run android

# Commands shortcuts
rfm: ## Calculate RFM scores
	@echo "${BLUE}Calculating RFM scores...${NC}"
	php artisan customers:calculate-rfm
	@echo "${GREEN}✓ RFM calculation complete${NC}"

at-risk: ## Detect at-risk customers
	@echo "${BLUE}Detecting at-risk customers...${NC}"
	php artisan customers:detect-at-risk
	@echo "${GREEN}✓ Detection complete${NC}"

report-daily: ## Generate daily report
	php artisan report:generate daily

report-weekly: ## Generate weekly report
	php artisan report:generate weekly

report-monthly: ## Generate monthly report
	php artisan report:generate monthly

# Help aliases
.DEFAULT_GOAL := help
