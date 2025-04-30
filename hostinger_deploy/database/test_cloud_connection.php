<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Cloud Database Connection Test</h1>";

// Check if form is submitted to update connection details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_config'])) {
    // Get the connection details from the form
    $db_host = $_POST['db_host'];
    $db_name = $_POST['db_name'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass'];
    $db_port = $_POST['db_port'];
    
    // Update the configuration file
    $config_content = <<<EOT
<?php
// Cloud Database configuration
\$db_host = '$db_host';  // Cloud database host
\$db_name = '$db_name';  // Cloud database name
\$db_user = '$db_user';  // Cloud database username
\$db_pass = '$db_pass';  // Cloud database password
\$db_port = $db_port;    // Database port

// Function to log database connection status
function logDbConnection(\$message) {
    \$logFile = __DIR__ . '/cloud_db_connection_log.txt';
    \$timestamp = date('Y-m-d H:i:s');
    \$logMessage = "[\$timestamp] \$message\\n";
    file_put_contents(\$logFile, \$logMessage, FILE_APPEND);
}

// Create connection
try {
    \$pdo = new PDO("mysql:host=\$db_host;port=\$db_port;dbname=\$db_name;charset=utf8mb4", \$db_user, \$db_pass);
    // Set the PDO error mode to exception
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    \$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // Disable emulation of prepared statements
    \$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // Log successful connection
    logDbConnection("Connected successfully to the cloud database: \$db_name@\$db_host");
} catch(PDOException \$e) {
    // Log connection error
    logDbConnection("Connection failed: " . \$e->getMessage());

    // Throw the exception to be handled by the calling script
    throw new PDOException(\$e->getMessage(), (int)\$e->getCode());
}
?>
EOT;
    
    // Save the updated configuration
    file_put_contents(__DIR__ . '/db_config_cloud.php', $config_content);
    
    echo "<div style='background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;'>
            Cloud database configuration updated successfully!
          </div>";
}

// Load the current configuration
$config_file = __DIR__ . '/db_config_cloud.php';
if (file_exists($config_file)) {
    // Extract the current values using regex to avoid executing the file if it has placeholders
    $config_content = file_get_contents($config_file);
    
    preg_match('/\$db_host\s*=\s*[\'"](.+?)[\'"]/i', $config_content, $host_matches);
    preg_match('/\$db_name\s*=\s*[\'"](.+?)[\'"]/i', $config_content, $name_matches);
    preg_match('/\$db_user\s*=\s*[\'"](.+?)[\'"]/i', $config_content, $user_matches);
    preg_match('/\$db_pass\s*=\s*[\'"](.+?)[\'"]/i', $config_content, $pass_matches);
    preg_match('/\$db_port\s*=\s*(\d+)/i', $config_content, $port_matches);
    
    $current_host = isset($host_matches[1]) ? $host_matches[1] : '';
    $current_name = isset($name_matches[1]) ? $name_matches[1] : '';
    $current_user = isset($user_matches[1]) ? $user_matches[1] : '';
    $current_pass = isset($pass_matches[1]) ? $pass_matches[1] : '';
    $current_port = isset($port_matches[1]) ? $port_matches[1] : '3306';
    
    // Don't load placeholder values
    if ($current_host === 'YOUR_CLOUD_DB_HOST') {
        $current_host = '';
        $current_name = '';
        $current_user = '';
        $current_pass = '';
    }
}

// Display the configuration form
?>
<div style="max-width: 800px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h2>Cloud Database Configuration</h2>
    <p>Enter your cloud database connection details below:</p>
    
    <form method="post" style="margin-top: 20px;">
        <div style="margin-bottom: 15px;">
            <label for="db_host" style="display: block; margin-bottom: 5px; font-weight: bold;">Database Host:</label>
            <input type="text" id="db_host" name="db_host" value="<?php echo htmlspecialchars($current_host); ?>" required 
                   style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            <small style="color: #666;">Example: mysql.hostinger.com, db.example.com, or IP address</small>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="db_name" style="display: block; margin-bottom: 5px; font-weight: bold;">Database Name:</label>
            <input type="text" id="db_name" name="db_name" value="<?php echo htmlspecialchars($current_name); ?>" required 
                   style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="db_user" style="display: block; margin-bottom: 5px; font-weight: bold;">Database Username:</label>
            <input type="text" id="db_user" name="db_user" value="<?php echo htmlspecialchars($current_user); ?>" required 
                   style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="db_pass" style="display: block; margin-bottom: 5px; font-weight: bold;">Database Password:</label>
            <input type="password" id="db_pass" name="db_pass" value="<?php echo htmlspecialchars($current_pass); ?>" required 
                   style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="db_port" style="display: block; margin-bottom: 5px; font-weight: bold;">Database Port:</label>
            <input type="number" id="db_port" name="db_port" value="<?php echo htmlspecialchars($current_port); ?>" required 
                   style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            <small style="color: #666;">Default MySQL port is 3306</small>
        </div>
        
        <button type="submit" name="update_config" style="background-color: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
            Update Configuration
        </button>
    </form>
    
    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #ddd;">
    
    <h2>Test Connection</h2>
    <p>After updating your configuration, click the button below to test the connection to your cloud database:</p>
    
    <form method="post" action="test_cloud_connection_result.php">
        <button type="submit" style="background-color: #2196F3; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
            Test Connection
        </button>
    </form>
    
    <div style="margin-top: 30px;">
        <h3>Need Help?</h3>
        <p>To find your cloud database connection details:</p>
        <ul>
            <li>For Hostinger: Log in to your Hostinger account, go to "Hosting" > "Databases" > "MySQL Databases"</li>
            <li>For other cloud providers: Check your database dashboard or settings page</li>
        </ul>
        <p>Make sure your cloud database allows remote connections from your IP address.</p>
    </div>
</div>
