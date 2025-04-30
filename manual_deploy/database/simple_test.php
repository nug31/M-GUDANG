<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Simple Database Test</h1>";

// Include database configuration
require_once 'hostinger_db_config.php';

echo "<p>Connected to database successfully!</p>";

// Test query
$result = $mysqli->query("SHOW TABLES");

if ($result) {
    echo "<h2>Tables in database:</h2>";
    echo "<ul>";
    while ($row = $result->fetch_row()) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Error: " . $mysqli->error . "</p>";
}

// Close connection
$mysqli->close();
?>
