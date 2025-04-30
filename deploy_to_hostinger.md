# Deploying to Hostinger Cloud VPS

This guide will help you deploy your Gudang Mitra application to a Hostinger Cloud VPS.

## Prerequisites

1. A Hostinger Cloud VPS with:
   - Ubuntu/Debian operating system
   - SSH access
   - Root or sudo privileges

2. Your application built for production:
   ```bash
   npm run build
   ```

## Step 1: Set up your VPS

1. Connect to your VPS via SSH:
   ```bash
   ssh username@your-vps-ip
   ```

2. Update your system:
   ```bash
   sudo apt update
   sudo apt upgrade -y
   ```

3. Install required packages:
   ```bash
   sudo apt install -y nginx mysql-server php php-fpm php-mysql php-mbstring php-xml php-curl php-zip php-gd
   ```

4. Start and enable services:
   ```bash
   sudo systemctl start nginx
   sudo systemctl enable nginx
   sudo systemctl start mysql
   sudo systemctl enable mysql
   ```

## Step 2: Set up MySQL Database

1. Secure MySQL installation:
   ```bash
   sudo mysql_secure_installation
   ```

2. Create a database and user:
   ```bash
   sudo mysql -u root -p
   ```

3. In the MySQL prompt, run:
   ```sql
   CREATE DATABASE itemtrack;
   CREATE USER 'itemtrack_user'@'localhost' IDENTIFIED BY 'your_secure_password';
   GRANT ALL PRIVILEGES ON itemtrack.* TO 'itemtrack_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

4. Import your database schema:
   ```bash
   mysql -u itemtrack_user -p itemtrack < database/itemtrack_db.sql
   ```

## Step 3: Configure Nginx

1. Create a new Nginx configuration file:
   ```bash
   sudo nano /etc/nginx/sites-available/gudangmitra
   ```

2. Add the following configuration:
   ```nginx
   server {
       listen 80;
       server_name your-vps-ip-or-domain.com;
       root /var/www/gudangmitra;
       index index.html index.php;

       location / {
           try_files $uri $uri/ /index.html;
       }

       location /database {
           try_files $uri $uri/ /database/index.php?$query_string;
           
           location ~ \.php$ {
               include snippets/fastcgi-php.conf;
               fastcgi_pass unix:/var/run/php/php-fpm.sock;
               fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
               include fastcgi_params;
           }
       }

       location ~ \.php$ {
           include snippets/fastcgi-php.conf;
           fastcgi_pass unix:/var/run/php/php-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
           include fastcgi_params;
       }
   }
   ```

3. Enable the site:
   ```bash
   sudo ln -s /etc/nginx/sites-available/gudangmitra /etc/nginx/sites-enabled/
   sudo nginx -t
   sudo systemctl restart nginx
   ```

## Step 4: Deploy Your Application

1. Create the web directory:
   ```bash
   sudo mkdir -p /var/www/gudangmitra
   sudo chown -R $USER:$USER /var/www/gudangmitra
   ```

2. On your local machine, transfer the files to your VPS:
   ```bash
   # Transfer the frontend build
   scp -r dist/* username@your-vps-ip:/var/www/gudangmitra/
   
   # Transfer the PHP backend files
   scp -r database username@your-vps-ip:/var/www/gudangmitra/
   ```

3. Update database configuration on the VPS:
   ```bash
   ssh username@your-vps-ip
   nano /var/www/gudangmitra/database/db_config.php
   ```

4. Update the database configuration with your VPS credentials:
   ```php
   <?php
   // Database configuration
   $db_host = 'localhost';
   $db_name = 'itemtrack';
   $db_user = 'itemtrack_user';
   $db_pass = 'your_secure_password';
   
   // Rest of the file...
   ?>
   ```

## Step 5: Set Permissions

1. Set proper permissions for the web directory:
   ```bash
   sudo chown -R www-data:www-data /var/www/gudangmitra
   sudo chmod -R 755 /var/www/gudangmitra
   ```

2. Make sure upload directories are writable:
   ```bash
   sudo mkdir -p /var/www/gudangmitra/uploads
   sudo chmod -R 775 /var/www/gudangmitra/uploads
   sudo chown -R www-data:www-data /var/www/gudangmitra/uploads
   ```

## Step 6: Test Your Deployment

1. Visit your site in a web browser:
   ```
   http://your-vps-ip-or-domain.com
   ```

2. Test the API endpoints:
   ```
   http://your-vps-ip-or-domain.com/database/api.php
   ```

## Troubleshooting

### PHP Errors
Check PHP error logs:
```bash
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/log/php-fpm/www-error.log
```

### Database Connection Issues
Verify your database credentials and connection:
```bash
php -r "try { new PDO('mysql:host=localhost;dbname=itemtrack', 'itemtrack_user', 'your_secure_password'); echo 'Connected successfully'; } catch(PDOException \$e) { echo 'Connection failed: ' . \$e->getMessage(); }"
```

### CORS Issues
If you encounter CORS issues, make sure your PHP files include the appropriate headers:
```php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
```

### File Permissions
If you encounter permission issues:
```bash
sudo find /var/www/gudangmitra -type d -exec chmod 755 {} \;
sudo find /var/www/gudangmitra -type f -exec chmod 644 {} \;
sudo chown -R www-data:www-data /var/www/gudangmitra
```
