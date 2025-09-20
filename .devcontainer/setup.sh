#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}🚀 Setting up Laravel Development Environment...${NC}"

# Find the Laravel project directory
LARAVEL_DIR=""
if [ -d "/workspace/project" ]; then
    LARAVEL_DIR="/workspace/project"
elif [ -d "/workspace/app" ]; then
    LARAVEL_DIR="/workspace/app"
elif [ -d "/workspace/src" ]; then
    LARAVEL_DIR="/workspace/src"
else
    # Search for composer.json in subdirectories
    COMPOSER_FILE=$(find /workspace -name "composer.json" -not -path "*/vendor/*" -not -path "*/node_modules/*" | head -1)
    if [ ! -z "$COMPOSER_FILE" ]; then
        LARAVEL_DIR=$(dirname "$COMPOSER_FILE")
    fi
fi

if [ -z "$LARAVEL_DIR" ]; then
    echo -e "${RED}❌ Could not find Laravel project directory. Please ensure your Laravel project is in a subdirectory.${NC}"
    echo -e "${YELLOW}💡 Common locations: /workspace/project, /workspace/app, /workspace/src${NC}"
    exit 1
fi

echo -e "${GREEN}📁 Found Laravel project at: $LARAVEL_DIR${NC}"

# Change to Laravel directory
cd "$LARAVEL_DIR"

# Check if it's a Laravel project
if [ ! -f "composer.json" ]; then
    echo -e "${RED}❌ composer.json not found in $LARAVEL_DIR${NC}"
    exit 1
fi

# Install PHP dependencies
echo -e "${GREEN}📦 Installing PHP dependencies with Composer...${NC}"
composer install --no-interaction --prefer-dist --optimize-autoloader

# Check if .env file exists, if not copy from .env.example
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        echo -e "${GREEN}⚙️ Creating .env file from .env.example...${NC}"
        cp .env.example .env
    else
        echo -e "${YELLOW}⚠️ No .env.example found. Creating basic .env file...${NC}"
        cat > .env << EOL
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="\${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="\${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="\${PUSHER_HOST}"
VITE_PUSHER_PORT="\${PUSHER_PORT}"
VITE_PUSHER_SCHEME="\${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="\${PUSHER_APP_CLUSTER}"
EOL
    fi
fi

# Generate Laravel application key
echo -e "${GREEN}🔑 Generating Laravel application key...${NC}"
php artisan key:generate --no-interaction

# Install Node.js dependencies if package.json exists
if [ -f "package.json" ]; then
    echo -e "${GREEN}📦 Installing Node.js dependencies...${NC}"
    npm install
fi

# Set proper permissions
echo -e "${GREEN}🔧 Setting proper permissions...${NC}"
sudo chown -R vscode:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Wait for MySQL to be ready
echo -e "${GREEN}🔄 Waiting for MySQL to be ready...${NC}"
timeout=60
while ! mysqladmin ping -h mysql -u laravel_user -plaravel_password --silent; do
    timeout=$((timeout - 1))
    if [ $timeout -eq 0 ]; then
        echo -e "${RED}❌ MySQL connection timeout${NC}"
        exit 1
    fi
    sleep 1
done

# Run database migrations
echo -e "${GREEN}🗄️ Running database migrations...${NC}"
php artisan migrate --no-interaction --force || echo -e "${YELLOW}⚠️ Migration failed. You may need to run 'php artisan migrate' manually.${NC}"

# Clear caches
echo -e "${GREEN}🧹 Clearing Laravel caches...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create symbolic link for storage
echo -e "${GREEN}🔗 Creating storage symbolic link...${NC}"
php artisan storage:link || echo -e "${YELLOW}⚠️ Storage link already exists or failed to create.${NC}"

# Display success message
echo -e "${GREEN}✅ Laravel development environment setup complete!${NC}"
echo -e "${YELLOW}📋 Next steps:${NC}"
echo -e "  1. Open terminal and navigate to: $LARAVEL_DIR"
echo -e "  2. Run: ${GREEN}php artisan serve --host=0.0.0.0 --port=8000${NC}"
echo -e "  3. Access your application at: ${GREEN}http://localhost:8000${NC}"
echo -e "  4. Access phpMyAdmin at: ${GREEN}http://localhost:8080${NC}"
echo -e ""
echo -e "${YELLOW}🛠️ Useful commands:${NC}"
echo -e "  • ${GREEN}php artisan serve --host=0.0.0.0${NC} - Start development server"
echo -e "  • ${GREEN}php artisan migrate${NC} - Run database migrations"
echo -e "  • ${GREEN}php artisan db:seed${NC} - Run database seeders"
echo -e "  • ${GREEN}composer install${NC} - Install PHP dependencies"
echo -e "  • ${GREEN}npm run dev${NC} - Build frontend assets for development"
echo -e "  • ${GREEN}npm run build${NC} - Build frontend assets for production"