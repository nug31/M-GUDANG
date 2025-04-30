<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Enable CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

echo "<h1>Simple Login Test</h1>";

// Include database configuration
require_once 'hostinger_db_config.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // Validate input
    if (empty($email) || empty($password)) {
        echo "<p style='color:red'>Email and password are required</p>";
    } else {
        // Check user credentials
        $stmt = $mysqli->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                echo "<p style='color:green'>Login successful!</p>";
                echo "<p>Welcome, " . htmlspecialchars($user['name']) . " (" . htmlspecialchars($user['role']) . ")</p>";
            } else {
                echo "<p style='color:red'>Invalid password</p>";
            }
        } else {
            echo "<p style='color:red'>User not found</p>";
        }
        
        $stmt->close();
    }
}

// Display login form
echo "
<form method='post' action=''>
    <div>
        <label for='email'>Email:</label>
        <input type='email' id='email' name='email' required>
    </div>
    <div>
        <label for='password'>Password:</label>
        <input type='password' id='password' name='password' required>
    </div>
    <div>
        <button type='submit'>Login</button>
    </div>
</form>
";

// Close connection
$mysqli->close();
?>
