<?php
// Hostinger Cloud VPS Database Configuration

// Function to log database connection status
function logDbConnection($message) {
    $logFile = __DIR__ . '/db_connection_log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Database credentials for gudang.nugijourney.com
$db_host = 'localhost'; // Usually 'localhost' for Hostinger
$db_name = 'itemtrack'; // Your database name from the Cloud Panel
$db_user = 'itemtrack'; // Your database username from the Cloud Panel
$db_pass = 'Reddevils94_'; // Your actual database password

// Create connection
try {
    // Log connection attempt
    logDbConnection("Attempting to connect to database: $db_name@$db_host as user: $db_user");

    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($mysqli->connect_error) {
        logDbConnection("Connection failed: " . $mysqli->connect_error);
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Log successful connection
    logDbConnection("Connected successfully to the database using mysqli");

    // For PDO connections
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // Log successful PDO connection
    logDbConnection("Connected successfully to the database using PDO");

} catch (Exception $e) {
    logDbConnection("Database connection error: " . $e->getMessage());
    die("Database connection error: " . $e->getMessage());
}
?>
