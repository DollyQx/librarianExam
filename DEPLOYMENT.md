# Deployment Guide for Librarian Exam Preparation Platform

This guide provides step-by-step instructions for deploying the **Librarian Exam Preparation Platform** to production environments, including **Shared Web Hosting (cPanel / Plesk)** and **Dedicated Virtual Private Servers (VPS - Ubuntu / Nginx / Apache)**.

---

## 📋 System & Server Requirements

| Component | Minimum Requirement | Recommended |
|---|---|---|
| **PHP Version** | PHP 8.2+ | PHP 8.2 or 8.3 |
| **PHP Extensions** | `BCMath`, `Ctype`, `Fileinfo`, `JSON`, `Mbstring`, `OpenSSL`, `PDO`, `Tokenizer`, `XML`, `GD` | All enabled by default on standard Laravel hosts |
| **Database** | MySQL 8.0+ or MariaDB 10.3+ | MySQL 8.0 |
| **Web Server** | Apache (`mod_rewrite` enabled) or Nginx | Nginx + PHP-FPM |
| **SSL Certificate** | Required for HTTPS | Free Let's Encrypt / AutoSSL |

---

## 🚀 Deployment Option A: Shared Web Hosting (cPanel / Plesk)

### Step 1: Upload Project Files
1. Compress your project directory (excluding `node_modules`, `vendor`, `.env`, and local storage test files) into a `.zip` archive.
2. Log into cPanel / File Manager.
3. Upload and extract the project files into your root home directory (e.g., `/home/username/librarianExam`).
4. Move the contents of the `public/` directory into your `public_html` directory (or point your domain document root directly to `/home/username/librarianExam/public`).

### Step 2: Configure `index.php` (If `public_html` path is modified)
If your application files sit outside `public_html`, update `public_html/index.php`:
```php
// Update vendor autoload path
require __DIR__.'/../librarianExam/vendor/autoload.php';

// Update bootstrap app path
$app = require_once __DIR__.'/../librarianExam/bootstrap/app.php';
```

### Step 3: Database Creation & Migration
1. Go to **cPanel > MySQL Database Wizard**.
2. Create a database (e.g., `user_librariandb`) and a user with a strong password. Grant **All Privileges**.
3. Create a `.env` file in your main application folder by copying `.env.example`:
   ```env
   APP_NAME="Librarian Exam Prep"
   APP_ENV=production
   APP_KEY=base64:... (generate locally via 'php artisan key:generate')
   APP_DEBUG=false
   APP_URL=https://yourdomain.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=user_librariandb
   DB_USERNAME=user_dbuser
   DB_PASSWORD=your_secure_password
   ```
4. Access SSH via cPanel Terminal or run migrations via PHP script:
   ```bash
   php artisan migrate --force
   ```

### Step 4: Storage Symlink Creation (`storage:link`)
On shared hosting, create a symlink from `storage/app/public` to `public/storage`:
- Via SSH:
  ```bash
  php artisan storage:link
  ```
- Or via a temporary PHP script in `public_html/symlink.php`:
  ```php
  <?php
  symlink('/home/username/librarianExam/storage/app/public', '/home/username/public_html/storage');
  echo "Symlink created!";
  ```

---

## ⚡ Deployment Option B: VPS (Nginx + PHP 8.2 + MySQL)

### Step 1: Clone Repository & Install Dependencies
```bash
cd /var/www
git clone https://github.com/your-username/librarianExam.git
cd librarianExam
composer install --optimize-autoloader --no-dev
```

### Step 2: Set Environment & Directory Permissions
```bash
cp .env.example .env
php artisan key:generate
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Step 3: Run Database Migrations
```bash
php artisan migrate --force
php artisan storage:link
```

### Step 4: Nginx Server Block Configuration
Create `/etc/nginx/sites-available/librarianExam`:
```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/librarianExam/public;

    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Enable site and restart Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/librarianExam /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## 🛡️ Production Security & Optimization Checklist

1. **Disable Debug Mode**: Confirm `APP_DEBUG=false` in `.env`.
2. **Cache Configurations & Routes**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
3. **HTTPS / SSL Certificate**: Obtain free SSL via Certbot:
   ```bash
   sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
   ```
4. **AdSense Readiness**: Reusable ad container component is ready at `@include('partials.ad_container')`.
