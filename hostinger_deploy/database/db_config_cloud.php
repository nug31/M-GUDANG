<?php
// Cloud Database configuration
$db_host = '145.79.11.48';  // Your server IP address
$db_name = 'itemtrack';     // Your database name
$db_user = 'itemtrack';     // Your database username
$db_pass = 'Reddevils94_';  // Your database password (using the one from production config)
$db_port = 3306;            // Default MySQL port

// Function to log database connection status
function logDbConnection($message) {
    $logFile = __DIR__ . '/cloud_db_connection_log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Create connection
try {
    $pdo = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // Disable emulation of prepared statements
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // Log successful connection
    logDbConnection("Connected successfully to the cloud database: $db_name@$db_host");
} catch(PDOException $e) {
    // Log connection error
    logDbConnection("Connection failed: " . $e->getMessage());

    // Throw the exception to be handled by the calling script
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>
