<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Database Connection Test</h1>";

// Include database configuration
// Uncomment the configuration file you want to use
require_once 'db_config_production.php';  // For production database
// require_once 'db_config.php';  // For local development database

echo "<p>Using database: {$db_name} on host: {$db_host}</p>";

// Test query to fetch users
try {
    // Test the connection by getting MySQL version
    $stmt = $pdo->query("SELECT VERSION() as version");
    $version = $stmt->fetch();

    echo "<p style='color:green'>Successfully connected to MySQL version: " . $version['version'] . "</p>";

    // Get database tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "<h2>Database Tables</h2>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>{$table}</li>";
    }
    echo "</ul>";

    // Test query to fetch users
    $stmt = $pdo->query("SELECT * FROM users LIMIT 10");
    $users = $stmt->fetchAll();

    echo "<h2>Users in the database (limited to 10):</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>";

    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['name'] . "</td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "<td>" . $user['role'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    // Test query to fetch items
    if (in_array('items', $tables)) {
        $stmt = $pdo->query("SELECT * FROM items LIMIT 10");
        $items = $stmt->fetchAll();

        echo "<h2>Items in the database (limited to 10):</h2>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Name</th><th>Category</th><th>Quantity</th></tr>";

        foreach ($items as $item) {
            echo "<tr>";
            echo "<td>" . $item['id'] . "</td>";
            echo "<td>" . $item['name'] . "</td>";
            echo "<td>" . $item['category'] . "</td>";
            echo "<td>" . $item['quantity'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    }

    // Test query to fetch requests with requester information
    if (in_array('requests', $tables) && in_array('users', $tables)) {
        $stmt = $pdo->query("
            SELECT r.*, u.name as requester_name, u.email as requester_email
            FROM requests r
            JOIN users u ON r.requester_id = u.id
            LIMIT 10
        ");
        $requests = $stmt->fetchAll();

        echo "<h2>Requests in the database (limited to 10):</h2>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Requester</th><th>Status</th><th>Date</th></tr>";

        foreach ($requests as $request) {
            echo "<tr>";
            echo "<td>" . $request['id'] . "</td>";
            echo "<td>" . $request['requester_name'] . "</td>";
            echo "<td>" . $request['status'] . "</td>";
            echo "<td>" . $request['created_at'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    }

} catch(PDOException $e) {
    echo "<p style='color:red'>Query failed: " . $e->getMessage() . "</p>";
}
?>
