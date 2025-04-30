# Deploying to gudang.nugijourney.com

This guide provides specific instructions for deploying your Gudang Mitra application to your Hostinger Cloud VPS at gudang.nugijourney.com (IP: 145.79.11.48).

## Prerequisites

1. Access to your Hostinger Cloud Panel
2. SSH access to your VPS
3. Your application built for production:
   ```bash
   npm run build
   ```

## Step 1: Build Your Application

1. Make sure your `.env.production` file has the correct settings:

   ```
   VITE_API_URL=https://gudang.nugijourney.com
   VITE_API_BASE_PATH=/database/api.php
   VITE_REGISTER_API_PATH=/database/simple_login.php
   VITE_REQUEST_API_PATH=/database/simple_request_handler.php
   VITE_ITEM_API_PATH=/database/simple_add_item.php
   VITE_USER_API_PATH=/database/simple_user_management.php
   VITE_CATEGORY_API_PATH=/database/simple_category_handler.php
   ```

2. Build your application:
   ```bash
   npm run build
   ```

## Step 2: Connect to Your VPS

1. Connect to your VPS via SSH:

   ```bash
   ssh gudang@145.79.11.48
   ```

2. Enter your password when prompted.

## Step 3: Set Up Web Directory

1. Create a directory for your application (if it doesn't exist):

   ```bash
   mkdir -p /home/gudang/public_html
   ```

2. Make sure you have permission to write to this directory:
   ```bash
   sudo chown -R gudang:gudang /home/gudang/public_html
   ```

## Step 4: Upload Your Files

You have several options to upload your files:

### Option 1: Using SCP (from your local machine)

```bash
# Upload the frontend build
scp -r dist/* gudang@145.79.11.48:/home/gudang/public_html/

# Upload the PHP backend files
scp -r database gudang@145.79.11.48:/home/gudang/public_html/
```

### Option 2: Using the File Manager in Hostinger Cloud Panel

1. Navigate to the File Manager in your Hostinger Cloud Panel
2. Upload the contents of your `dist` directory to `/home/gudang/public_html/`
3. Upload your `database` directory to `/home/gudang/public_html/`

### Option 3: Using FTP/SFTP

Use an FTP client like FileZilla to connect to your server and upload the files.

## Step 5: Configure Your Database

1. Your database 'itemtrack' is already created in the Hostinger Cloud Panel.

2. Update the database configuration file:

   ```bash
   nano /home/gudang/public_html/database/db_config.php
   ```

3. Update the file with your database credentials:

   ```php
   <?php
   // Database configuration
   $db_host = 'localhost';
   $db_name = 'itemtrack';
   $db_user = 'itemtrack';
   $db_pass = 'Reddevils94_'; // Your actual database password

   // Function to log database connection status
   function logDbConnection($message) {
       $logFile = __DIR__ . '/db_connection_log.txt';
       $timestamp = date('Y-m-d H:i:s');
       $logMessage = "[$timestamp] $message\n";
       file_put_contents($logFile, $logMessage, FILE_APPEND);
   }

   // Create connection
   try {
       $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
       // Set the PDO error mode to exception
       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       // Set default fetch mode to associative array
       $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
       // Disable emulation of prepared statements
       $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

       // Log successful connection
       logDbConnection("Connected successfully to the database: $db_name@$db_host");
   } catch(PDOException $e) {
       // Log connection error
       logDbConnection("Connection failed: " . $e->getMessage());

       // Throw the exception to be handled by the calling script
       throw new PDOException($e->getMessage(), (int)$e->getCode());
   }
   ?>
   ```

4. Import your database schema (if not already imported):
   ```bash
   mysql -u itemtrack -p itemtrack < /home/gudang/public_html/database/itemtrack_db.sql
   ```

## Step 6: Set Permissions

1. Set proper permissions for the web directory:

   ```bash
   find /home/gudang/public_html -type d -exec chmod 755 {} \;
   find /home/gudang/public_html -type f -exec chmod 644 {} \;
   ```

2. Make sure PHP files are executable:

   ```bash
   find /home/gudang/public_html -name "*.php" -exec chmod 755 {} \;
   ```

3. Make sure upload directories are writable:
   ```bash
   mkdir -p /home/gudang/public_html/uploads
   chmod -R 775 /home/gudang/public_html/uploads
   ```

## Step 7: Configure Web Server

Hostinger typically uses LiteSpeed or Apache. Make sure you have a proper `.htaccess` file in your root directory:

```bash
nano /home/gudang/public_html/.htaccess
```

Add the following content:

```
# Enable rewriting
RewriteEngine On

# If the request is not for a file or directory
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# Rewrite all requests to the index.html
RewriteRule ^ index.html [QSA,L]

# Set CORS headers
<IfModule mod_headers.c>
    Header set Access-Control-Allow-Origin "*"
    Header set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"
    Header set Access-Control-Allow-Headers "Content-Type, Authorization"
</IfModule>

# PHP settings
<IfModule mod_php7.c>
    php_flag display_errors Off
    php_value upload_max_filesize 10M
    php_value post_max_size 10M
    php_value max_execution_time 300
    php_value max_input_time 300
</IfModule>
```

## Step 8: Test Your Deployment

1. Visit your site in a web browser:

   ```
   https://gudang.nugijourney.com
   ```

2. Test the API endpoints:
   ```
   https://gudang.nugijourney.com/database/api.php
   ```

## Troubleshooting

### PHP Errors

Check PHP error logs:

```bash
tail -f /home/gudang/logs/error.log
```

### Database Connection Issues

Verify your database credentials and connection:

```bash
php -r "try { new PDO('mysql:host=localhost;dbname=itemtrack', 'itemtrack', 'Reddevils94_'); echo 'Connected successfully'; } catch(PDOException \$e) { echo 'Connection failed: ' . \$e->getMessage(); }"
```

### File Permissions

If you encounter permission issues:

```bash
sudo find /home/gudang/public_html -type d -exec chmod 755 {} \;
sudo find /home/gudang/public_html -type f -exec chmod 644 {} \;
sudo find /home/gudang/public_html -name "*.php" -exec chmod 755 {} \;
```

### SSL/HTTPS Issues

If you're having issues with HTTPS, make sure SSL is properly configured in your Hostinger Cloud Panel.

### CORS Issues

If you encounter CORS issues, make sure your PHP files include the appropriate headers:

```php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
```
