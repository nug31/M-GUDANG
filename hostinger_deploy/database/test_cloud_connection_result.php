<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Cloud Database Connection Test Results</h1>";

// Function to display a message box
function displayMessage($message, $type = 'info') {
    $color = ($type == 'success') ? '#d4edda' : (($type == 'error') ? '#f8d7da' : '#cce5ff');
    $textColor = ($type == 'success') ? '#155724' : (($type == 'error') ? '#721c24' : '#004085');
    
    echo "<div style='background-color: $color; color: $textColor; padding: 10px; border-radius: 5px; margin-bottom: 20px;'>
            $message
          </div>";
}

// Check if the cloud database configuration file exists
$config_file = __DIR__ . '/db_config_cloud.php';
if (!file_exists($config_file)) {
    displayMessage("Cloud database configuration file not found. Please create it first.", 'error');
    echo "<p><a href='test_cloud_connection.php' style='color: #007bff;'>Go back to configuration</a></p>";
    exit;
}

// Include the cloud database configuration
try {
    require_once $config_file;
    
    // If we get here, the connection was successful (the file throws an exception on failure)
    displayMessage("Successfully connected to the cloud database: $db_name@$db_host", 'success');
    
    // Test the connection by getting MySQL version
    $stmt = $pdo->query("SELECT VERSION() as version");
    $version = $stmt->fetch();
    
    echo "<p>MySQL Version: <strong>" . $version['version'] . "</strong></p>";
    
    // Get database information
    $stmt = $pdo->query("SELECT DATABASE() as db_name");
    $db = $stmt->fetch();
    
    echo "<p>Current database: <strong>" . $db['db_name'] . "</strong></p>";
    
    // List all tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>Database Tables</h2>";
    
    if (count($tables) > 0) {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li><strong>$table</strong>";
            
            // Get table structure
            $stmt = $pdo->query("DESCRIBE `$table`");
            $columns = $stmt->fetchAll();
            
            echo "<ul>";
            foreach ($columns as $column) {
                echo "<li>" . $column['Field'] . " - " . $column['Type'] . "</li>";
            }
            echo "</ul>";
            
            // Get row count
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
            $count = $stmt->fetch();
            echo "<p>Rows: " . $count['count'] . "</p>";
            
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No tables found in the database.</p>";
    }
    
    // Display connection information
    echo "<h2>Connection Information</h2>";
    echo "<ul>";
    echo "<li>Host: $db_host</li>";
    echo "<li>Database: $db_name</li>";
    echo "<li>Username: $db_user</li>";
    echo "<li>Port: $db_port</li>";
    echo "</ul>";
    
    // Display phpMyAdmin configuration instructions
    echo "<h2>phpMyAdmin Configuration</h2>";
    echo "<p>To configure phpMyAdmin to connect to this cloud database, you need to add a server configuration to your phpMyAdmin configuration file.</p>";
    
    echo "<h3>Method 1: Using config.inc.php</h3>";
    echo "<p>Add the following code to your phpMyAdmin config.inc.php file:</p>";
    
    $phpmyadmin_config = <<<EOT
/* Server configuration for cloud database */
\$i++;
\$cfg['Servers'][\$i]['host'] = '$db_host';
\$cfg['Servers'][\$i]['port'] = '$db_port';
\$cfg['Servers'][\$i]['user'] = '$db_user';
\$cfg['Servers'][\$i]['password'] = '$db_pass';
\$cfg['Servers'][\$i]['auth_type'] = 'config';
\$cfg['Servers'][\$i]['compress'] = false;
\$cfg['Servers'][\$i]['AllowNoPassword'] = false;
\$cfg['Servers'][\$i]['verbose'] = 'Cloud Database';
EOT;
    
    echo "<pre style='background-color: #f8f9fa; padding: 10px; border-radius: 5px; overflow-x: auto;'>" . htmlspecialchars($phpmyadmin_config) . "</pre>";
    
    echo "<h3>Method 2: Using Temporary Login</h3>";
    echo "<p>You can also connect to your cloud database directly from the phpMyAdmin login page:</p>";
    echo "<ol>";
    echo "<li>Open phpMyAdmin in your browser</li>";
    echo "<li>Enter the following information:";
    echo "<ul>";
    echo "<li>Server: $db_host</li>";
    echo "<li>Username: $db_user</li>";
    echo "<li>Password: (your password)</li>";
    echo "<li>Port: $db_port</li>";
    echo "</ul></li>";
    echo "</ol>";
    
    // Create a direct link to phpMyAdmin
    echo "<p><a href='http://localhost/phpmyadmin/' target='_blank' style='background-color: #4CAF50; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block;'>Open phpMyAdmin</a></p>";
    
} catch(PDOException $e) {
    displayMessage("Connection failed: " . $e->getMessage(), 'error');
    
    // Display troubleshooting information
    echo "<h2>Troubleshooting</h2>";
    echo "<ol>";
    echo "<li>Check that your cloud database host, username, and password are correct</li>";
    echo "<li>Make sure your cloud database allows remote connections from your IP address</li>";
    echo "<li>Check if your cloud provider requires an SSL connection</li>";
    echo "<li>Verify that the database exists and the user has access to it</li>";
    echo "<li>Check if your cloud provider has a firewall blocking the connection</li>";
    echo "</ol>";
}

// Back button
echo "<p><a href='test_cloud_connection.php' style='color: #007bff;'>Go back to configuration</a></p>";
?>
