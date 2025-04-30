<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Database Connection Test for gudang.nugijourney.com</h1>";

// Database configuration
$db_host = 'localhost';
$db_name = 'itemtrack';
$db_user = 'itemtrack';
$db_pass = 'Reddevils94_';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color:green'>Connected successfully to the database: $db_name@$db_host</p>";
    
    // Test query to check if the users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    $usersTableExists = $stmt->rowCount() > 0;
    
    echo "<p>Users table exists: " . ($usersTableExists ? '<span style="color:green">Yes</span>' : '<span style="color:red">No</span>') . "</p>";
    
    // If the users table exists, count the number of users
    if ($usersTableExists) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $count = $stmt->fetch()['count'];
        echo "<p>Number of users in the database: <strong>$count</strong></p>";
    }
    
    // Test query to check if the items table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'items'");
    $itemsTableExists = $stmt->rowCount() > 0;
    
    echo "<p>Items table exists: " . ($itemsTableExists ? '<span style="color:green">Yes</span>' : '<span style="color:red">No</span>') . "</p>";
    
    // If the items table exists, count the number of items
    if ($itemsTableExists) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM items");
        $count = $stmt->fetch()['count'];
        echo "<p>Number of items in the database: <strong>$count</strong></p>";
    }
    
    // Test query to check if the requests table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'requests'");
    $requestsTableExists = $stmt->rowCount() > 0;
    
    echo "<p>Requests table exists: " . ($requestsTableExists ? '<span style="color:green">Yes</span>' : '<span style="color:red">No</span>') . "</p>";
    
    // If the requests table exists, count the number of requests
    if ($requestsTableExists) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM requests");
        $count = $stmt->fetch()['count'];
        echo "<p>Number of requests in the database: <strong>$count</strong></p>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color:red'>Connection failed: " . $e->getMessage() . "</p>";
}
?>
