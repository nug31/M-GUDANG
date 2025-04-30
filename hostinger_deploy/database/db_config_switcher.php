<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to get current environment
function getCurrentEnvironment() {
    $configFile = __DIR__ . '/db_config.php';
    $content = file_get_contents($configFile);
    
    if (strpos($content, "require_once 'db_config_production.php';") !== false) {
        return 'production';
    } else {
        return 'local';
    }
}

// Function to switch environment
function switchEnvironment($environment) {
    $configFile = __DIR__ . '/db_config.php';
    $localConfig = <<<'EOT'
<?php
// Database configuration
$db_host = 'localhost';
$db_name = 'itemtrack';
$db_user = 'root';  // Default Laragon MySQL username
$db_pass = '';      // Default Laragon MySQL password (empty)

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
EOT;

    $productionConfig = <<<'EOT'
<?php
// Use production database configuration
require_once 'db_config_production.php';
?>
EOT;

    if ($environment === 'production') {
        file_put_contents($configFile, $productionConfig);
        return "Switched to production environment";
    } else {
        file_put_contents($configFile, $localConfig);
        return "Switched to local environment";
    }
}

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['switch_to'])) {
        $message = switchEnvironment($_POST['switch_to']);
    }
}

// Get current environment
$currentEnv = getCurrentEnvironment();

// Display the interface
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Configuration Switcher</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .current-env {
            background-color: #e9f7ef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .env-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        form {
            margin-top: 20px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .local-button {
            background-color: #2196F3;
        }
        .local-button:hover {
            background-color: #0b7dda;
        }
        .production-button {
            background-color: #f44336;
        }
        .production-button:hover {
            background-color: #d32f2f;
        }
        pre {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Configuration Switcher</h1>
        
        <?php if ($message): ?>
        <div class="message">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>
        
        <div class="current-env">
            <h2>Current Environment: <?php echo ucfirst($currentEnv); ?></h2>
            <p>The application is currently using the <strong><?php echo $currentEnv; ?></strong> database configuration.</p>
        </div>
        
        <div class="env-details">
            <h3>Environment Details:</h3>
            
            <h4>Local Environment</h4>
            <ul>
                <li>Host: localhost</li>
                <li>Database: itemtrack</li>
                <li>User: root</li>
                <li>Password: [empty]</li>
            </ul>
            
            <h4>Production Environment</h4>
            <ul>
                <li>Host: localhost</li>
                <li>Database: itemtrack</li>
                <li>User: itemtrack</li>
                <li>Password: [hidden]</li>
            </ul>
        </div>
        
        <div class="button-container">
            <form method="post">
                <input type="hidden" name="switch_to" value="local">
                <button type="submit" class="local-button">Switch to Local Environment</button>
            </form>
            
            <form method="post">
                <input type="hidden" name="switch_to" value="production">
                <button type="submit" class="production-button">Switch to Production Environment</button>
            </form>
        </div>
        
        <div style="margin-top: 30px;">
            <h3>Test Connection</h3>
            <p>After switching environments, you can test the connection using the test script:</p>
            <a href="test_connection.php" target="_blank">Test Database Connection</a>
        </div>
    </div>
</body>
</html>
