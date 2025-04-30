<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Database Schema Import Tool</h1>";

// Database configuration
$db_host = 'localhost';
$db_name = 'itemtrack';
$db_user = 'itemtrack';
$db_pass = 'Reddevils94_';

// Function to execute SQL from a file
function executeSQLFile($pdo, $sqlFile) {
    if (!file_exists($sqlFile)) {
        return "SQL file not found: $sqlFile";
    }
    
    $sql = file_get_contents($sqlFile);
    if (!$sql) {
        return "Could not read SQL file: $sqlFile";
    }
    
    // Split SQL by semicolons to get individual queries
    $queries = explode(';', $sql);
    $results = [];
    
    foreach ($queries as $query) {
        $query = trim($query);
        if (empty($query)) continue;
        
        try {
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $results[] = "Success: " . substr($query, 0, 50) . "...";
        } catch (PDOException $e) {
            $results[] = "Error: " . $e->getMessage() . " in query: " . substr($query, 0, 50) . "...";
        }
    }
    
    return $results;
}

// Check if we should import the schema
$importSchema = isset($_POST['import']) && $_POST['import'] == 'yes';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color:green'>Connected successfully to the database: $db_name@$db_host</p>";
    
    // Check existing tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>Existing Tables</h2>";
    echo "<ul>";
    if (count($tables) > 0) {
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
    } else {
        echo "<li>No tables found in the database.</li>";
    }
    echo "</ul>";
    
    // Import schema if requested
    if ($importSchema) {
        $sqlFile = __DIR__ . '/itemtrack_db.sql';
        echo "<h2>Importing Schema</h2>";
        
        $results = executeSQLFile($pdo, $sqlFile);
        
        echo "<ul>";
        foreach ($results as $result) {
            $color = strpos($result, 'Error') === 0 ? 'red' : 'green';
            echo "<li style='color:$color'>$result</li>";
        }
        echo "</ul>";
        
        // Check tables after import
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "<h2>Tables After Import</h2>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color:red'>Connection failed: " . $e->getMessage() . "</p>";
}

// Display import form
echo "<h2>Import Database Schema</h2>";
echo "<form method='post'>";
echo "<p>This will import the database schema from itemtrack_db.sql. Any existing tables will be preserved unless the SQL contains DROP TABLE statements.</p>";
echo "<input type='hidden' name='import' value='yes'>";
echo "<input type='submit' value='Import Schema'>";
echo "</form>";
?>
