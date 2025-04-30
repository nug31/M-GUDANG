# Hostinger Deployment Instructions

## Step 1: Upload Files
1. Upload all files and folders in this directory to your Hostinger public_html folder.

## Step 2: Update Database Configuration
1. Edit the file 'database/hostinger_db_config.php'
2. Replace 'YOUR_PASSWORD' with your actual Hostinger database password.

## Step 3: Set Up the Database
1. Visit https://yourdomain.com/database/simple_setup.php
2. This will create the necessary database tables and a default admin user.

## Step 4: Test the Connection
1. Visit https://yourdomain.com/database/simple_test.php
2. This will test the database connection and show the tables.

## Step 5: Test Login
1. Visit https://yourdomain.com/database/simple_login_test.php
2. Try logging in with the default admin credentials:
   - Email: admin@example.com
   - Password: admin123

## Step 6: Access Your Application
1. Visit https://yourdomain.com
2. You should now be able to use your application.

## Troubleshooting
If you encounter any issues:
1. Check the database configuration in 'database/hostinger_db_config.php'
2. Make sure your Hostinger database user has the correct privileges
3. Check the PHP error logs in your Hostinger control panel
