# 📚 Production Deployment Guide - Ebook API Integration

## 🎯 Overview

This Laravel application integrates with an external ebook API (`https://ebook.newsmaker.id/api`) to provide ebook management functionality. The system automatically syncs folders and ebooks from the API and disables admin input to ensure data consistency.

## 🚀 Production Deployment Steps

### 1. Prerequisites

- **PHP 8.1+** with required extensions
- **Composer** for dependency management
- **MySQL 5.7+** or compatible database
- **Web server** (Apache/Nginx) configured for Laravel
- **SSL certificate** (recommended for production)

### 2. Server Setup

#### Upload Files
```bash
# Upload all files to your web server
# Make sure the document root points to the 'public' directory
```

#### Set Permissions
```bash
# Set proper permissions for storage and bootstrap/cache
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs
```

### 3. Environment Configuration

Create/update your `.env` file with production settings:

```env
APP_NAME="Ebook Management System"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_APP_KEY
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=your_database_host
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_smtp_username
MAIL_PASSWORD=your_smtp_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Database Setup

#### Run Migrations
```bash
php artisan migrate --force
```

#### Verify Database Structure
The following tables should exist with API columns:
- `folder_ebooks` (with `api_id`, `api_data`, `synced_at` columns)
- `ebooks` (with `api_id`, `api_data`, `synced_at` columns)

### 5. Sync Data from API

#### Method 1: Artisan Command (Recommended)
```bash
# Sync all data from API
php artisan ebook:sync

# Sync with cache clearing
php artisan ebook:sync --clear-cache
```

#### Method 2: Direct Script (Fallback)
```bash
# If artisan command doesn't work, use direct script
php sync-ebook-api.php
```

#### Automatic Sync (Recommended)
Set up a cron job for daily sync:

```bash
# Add to crontab (crontab -e)
0 2 * * * cd /path/to/your/project && php artisan ebook:sync --clear-cache >> /dev/null 2>&1
```

### 6. Web Server Configuration

#### Apache Configuration
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /path/to/your/project/public

    <Directory /path/to/your/project/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

#### Nginx Configuration
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/your/project/public;

    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### 7. SSL Configuration (Recommended)

#### Using Let's Encrypt
```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Generate SSL certificate
sudo certbot --apache -d yourdomain.com
```

### 8. Testing Production Deployment

#### Test API Connection
```bash
php artisan tinker
```
```php
$apiService = app()->make(App\Services\EbookApiService::class);
$apiService->isApiAvailable(); // Should return true
```

#### Test Data Sync
```bash
# Try artisan command first
php artisan ebook:sync

# If that fails, use direct script
php sync-ebook-api.php
```

#### Check Database
```bash
php artisan tinker
```
```php
App\Models\FolderEbook::syncedFromApi()->count(); // Should show folder count
App\Models\Ebook::syncedFromApi()->count(); // Should show ebook count
```

### 9. Verify Web Interface

1. **Folders Page**: `https://yourdomain.com/ebook`
   - Should show API indicator badge
   - Should display synced folders
   - Admin buttons should be hidden/disabled

2. **Ebooks Page**: `https://yourdomain.com/ebook/{folder-slug}`
   - Should show ebooks from API
   - Download buttons should work
   - Should redirect to API file URLs

3. **Download Test**: Click any download button
   - Should redirect to `https://ebook.newsmaker.id/uploads/ebook/...`
   - PDF should download successfully

## 🔧 Troubleshooting

### Common Issues

#### 1. API Connection Failed
```bash
# Test API connectivity
php artisan tinker
```
```php
$apiService = app()->make(App\Services\EbookApiService::class);
$apiService->isApiAvailable();
```

**Solutions:**
- Check internet connection
- Verify API endpoint URL
- Check firewall settings
- Ensure HTTPS/SSL is properly configured

#### 2. Database Connection Issues
```bash
# Test database connection
php artisan migrate:status
```

**Solutions:**
- Verify database credentials in `.env`
- Check database server status
- Ensure database user has proper permissions

#### 3. Files Not Loading
**Solutions:**
- Check file permissions
- Verify web server configuration
- Clear application cache: `php artisan optimize:clear`

#### 4. Downloads Not Working
**Solutions:**
- Check API file URLs in database
- Verify external API is accessible
- Check browser console for errors

#### 5. Command Not Found Error
If you get "There are no commands defined in the 'ebook' namespace":

**Solutions:**
1. **Try the direct script method:**
   ```bash
   php sync-ebook-api.php
   ```

2. **Clear all caches:**
   ```bash
   php artisan optimize:clear
   php artisan config:clear
   php artisan cache:clear
   ```

3. **Check command registration:**
   ```bash
   php artisan list | grep ebook
   ```

4. **Manual command registration** (if needed):
   - Ensure `app/Console/Kernel.php` exists and includes the command
   - The command should be registered in the `$commands` array

### Debug Commands

```bash
# Check API service
php artisan tinker
$apiService = app()->make(App\Services\EbookApiService::class);
$apiService->isApiAvailable();

# Check database content
php artisan tinker
App\Models\FolderEbook::syncedFromApi()->get();
App\Models\Ebook::syncedFromApi()->get();

# Clear all caches
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear

# Check logs
tail -f storage/logs/laravel.log
```

## 📊 Monitoring

### Log Files
- Application logs: `storage/logs/laravel.log`
- Web server logs: Check your web server configuration

### Database Monitoring
```sql
-- Check sync status
SELECT folder_name, synced_at, ebooks_count
FROM folder_ebooks
WHERE api_id IS NOT NULL
ORDER BY synced_at DESC;

-- Check ebook sync status
SELECT title, synced_at
FROM ebooks
WHERE api_id IS NOT NULL
ORDER BY synced_at DESC;
```

### Cron Job Monitoring
```bash
# Check if cron is running
crontab -l

# Check cron logs
grep "ebook:sync" /var/log/cron.log
```

## 🔒 Security Considerations

1. **Environment Variables**: Never commit `.env` file to version control
2. **Database Credentials**: Use strong passwords and consider database user with limited permissions
3. **File Permissions**: Ensure proper file permissions to prevent unauthorized access
4. **HTTPS**: Always use SSL in production
5. **Firewall**: Configure firewall to allow only necessary ports
6. **Updates**: Keep Laravel and dependencies updated

## 📞 Support

If you encounter issues:

1. Check the troubleshooting section above
2. Review Laravel logs: `storage/logs/laravel.log`
3. Test API connectivity
4. Verify database structure and content
5. Check web server configuration

## 🎉 Success Checklist

- [ ] Files uploaded to server
- [ ] Environment configured (`.env`)
- [ ] Database migrations run
- [ ] API connection tested
- [ ] Data synced successfully (using either method)
- [ ] Web interface accessible
- [ ] Downloads working
- [ ] SSL configured (recommended)
- [ ] Cron job set up (recommended)

---

**Production URL**: https://edukasi.newsmaker.id/ebook

**API Endpoint**: https://ebook.newsmaker.id/api

**Contact**: For technical support, please refer to your system administrator or development team.
