<?php
// Hostinger Database Configuration
$db_host = 'localhost';
$db_name = 'u343415529_itemtrack';
$db_user = 'u343415529_itemtrack';
$db_pass = 'YOUR_PASSWORD'; // Replace with your actual Hostinger password

// Create connection
try {
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    
    // For PDO connections
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
?>
