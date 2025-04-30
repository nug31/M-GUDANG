<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Simple Database Setup</h1>";

// Database configuration
$db_host = 'localhost';
$db_name = 'u343415529_itemtrack';
$db_user = 'u343415529_itemtrack';
$db_pass = 'YOUR_PASSWORD'; // Replace with your actual Hostinger password

try {
    // Create connection
    $mysqli = new mysqli($db_host, $db_user, $db_pass);
    
    // Check connection
    if ($mysqli->connect_error) {
        throw new Exception("Connection failed: " . $mysqli->connect_error);
    }
    
    echo "<p>Connected to MySQL server successfully!</p>";
    
    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS `$db_name`";
    if ($mysqli->query($sql)) {
        echo "<p>Database created or already exists.</p>";
    } else {
        throw new Exception("Error creating database: " . $mysqli->error);
    }
    
    // Select database
    $mysqli->select_db($db_name);
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS `users` (
        `id` VARCHAR(36) NOT NULL,
        `name` VARCHAR(100) NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `role` ENUM('admin', 'manager', 'requester') NOT NULL DEFAULT 'requester',
        `department` VARCHAR(100) NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE INDEX `email_UNIQUE` (`email`)
    )";
    
    if ($mysqli->query($sql)) {
        echo "<p>Users table created successfully!</p>";
    } else {
        throw new Exception("Error creating users table: " . $mysqli->error);
    }
    
    // Create items table
    $sql = "CREATE TABLE IF NOT EXISTS `items` (
        `id` VARCHAR(36) NOT NULL,
        `name` VARCHAR(100) NOT NULL,
        `description` TEXT NULL,
        `category` VARCHAR(50) NOT NULL,
        `quantity` INT NOT NULL DEFAULT 0,
        `min_quantity` INT NOT NULL DEFAULT 0,
        `max_quantity` INT NOT NULL DEFAULT 0,
        `location` VARCHAR(100) NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    )";
    
    if ($mysqli->query($sql)) {
        echo "<p>Items table created successfully!</p>";
    } else {
        throw new Exception("Error creating items table: " . $mysqli->error);
    }
    
    // Create requests table
    $sql = "CREATE TABLE IF NOT EXISTS `requests` (
        `id` VARCHAR(36) NOT NULL,
        `project_name` VARCHAR(100) NOT NULL,
        `requester_id` VARCHAR(36) NOT NULL,
        `reason` TEXT NOT NULL,
        `priority` ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
        `due_date` DATE NULL,
        `status` ENUM('pending', 'approved', 'rejected', 'completed') NOT NULL DEFAULT 'pending',
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    )";
    
    if ($mysqli->query($sql)) {
        echo "<p>Requests table created successfully!</p>";
    } else {
        throw new Exception("Error creating requests table: " . $mysqli->error);
    }
    
    // Create request_items table
    $sql = "CREATE TABLE IF NOT EXISTS `request_items` (
        `request_id` VARCHAR(36) NOT NULL,
        `item_id` VARCHAR(36) NOT NULL,
        `quantity` INT NOT NULL DEFAULT 1,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`request_id`, `item_id`)
    )";
    
    if ($mysqli->query($sql)) {
        echo "<p>Request items table created successfully!</p>";
    } else {
        throw new Exception("Error creating request_items table: " . $mysqli->error);
    }
    
    // Insert default admin user if not exists
    $admin_id = 'admin123';
    $admin_name = 'Admin User';
    $admin_email = 'admin@example.com';
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $admin_role = 'admin';
    
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $admin_email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $stmt = $mysqli->prepare("INSERT INTO users (id, name, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $admin_id, $admin_name, $admin_email, $admin_password, $admin_role);
        
        if ($stmt->execute()) {
            echo "<p>Default admin user created successfully!</p>";
        } else {
            throw new Exception("Error creating admin user: " . $stmt->error);
        }
    } else {
        echo "<p>Admin user already exists.</p>";
    }
    
    echo "<h2>Database setup completed successfully!</h2>";
    echo "<p>You can now use the application with the following default admin user:</p>";
    echo "<ul>";
    echo "<li>Email: admin@example.com</li>";
    echo "<li>Password: admin123</li>";
    echo "</ul>";
    
    // Close connection
    $mysqli->close();
    
} catch (Exception $e) {
    echo "<h2 style='color:red'>Error: " . $e->getMessage() . "</h2>";
}
?>
