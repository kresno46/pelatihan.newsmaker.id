#!/bin/bash

# Production Deployment Script for Ebook API Integration
# Usage: bash deploy-production-v2.sh

echo "🚀 Starting production deployment for ebook API integration..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "artisan file not found. Please run this script from the Laravel project root."
    exit 1
fi

print_status "Current directory: $(pwd)"

# 1. Check PHP and Composer
print_status "Checking PHP and Composer..."
php --version
composer --version

# 2. Install dependencies
print_status "Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# 3. Clear all caches
print_status "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 4. Generate application key if not exists
if ! grep -q "APP_KEY=" .env; then
    print_status "Generating application key..."
    php artisan key:generate
fi

# 5. Run database migrations
print_status "Running database migrations..."
php artisan migrate --force

# 6. Clear config cache and cache it again
print_status "Caching configuration..."
php artisan config:cache

# 7. Optimize application
print_status "Optimizing application..."
php artisan optimize

# 8. Set proper permissions (if on Linux/Unix)
if [[ "$OSTYPE" != "darwin"* ]] && [[ "$OSTYPE" != "msys" ]] && [[ "$OSTYPE" != "cygwin" ]]; then
    print_status "Setting proper permissions..."
    chmod -R 755 storage bootstrap/cache
    chmod -R 775 storage/logs
fi

# 9. Test API connection
print_status "Testing API connection..."
php artisan tinker --execute="
try {
    \$apiService = app()->make(App\Services\EbookApiService::class);
    if (\$apiService->isApiAvailable()) {
        echo '✅ API is available';
    } else {
        echo '❌ API is not available';
    }
} catch (Exception \$e) {
    echo '❌ API test failed: ' . \$e->getMessage();
}
"

# 10. Sync data from API (try artisan command first, fallback to direct script)
print_status "Syncing data from API..."
if php artisan ebook:sync --clear-cache; then
    print_status "✅ Artisan command sync successful"
else
    print_warning "Artisan command failed, trying direct script..."
    if php sync-ebook-api.php; then
        print_status "✅ Direct script sync successful"
    else
        print_error "❌ Both sync methods failed"
        exit 1
    fi
fi

# 11. Check sync results
print_status "Checking sync results..."
php artisan tinker --execute="
\$folders = App\Models\FolderEbook::syncedFromApi()->count();
\$ebooks = App\Models\Ebook::syncedFromApi()->count();
echo \"Folders synced: {\$folders}\n\";
echo \"Ebooks synced: {\$ebooks}\n\";
"

print_status "🎉 Production deployment completed!"
print_warning "Don't forget to:"
echo "  - Update your .env file with production database credentials"
echo "  - Set APP_ENV=production in your .env file"
echo "  - Configure your web server (Apache/Nginx) to point to the public directory"
echo "  - Set up cron jobs for automatic syncing if needed"
echo "  - Configure SSL certificate for HTTPS"

print_status "To sync data manually in production:"
echo "  Method 1: php artisan ebook:sync"
echo "  Method 2: php sync-ebook-api.php"

print_status "To set up automatic daily sync, add this to your crontab:"
echo "  0 2 * * * cd $(pwd) && php artisan ebook:sync --clear-cache >> /dev/null 2>&1"

print_status "Production deployment script completed successfully! ✅"
