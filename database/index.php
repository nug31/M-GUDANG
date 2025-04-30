<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Management Tools</title>
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
        .tool-section {
            margin-bottom: 30px;
        }
        .tool-link {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .tool-link:hover {
            background-color: #45a049;
        }
        .description {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Management Tools</h1>

        <div class="tool-section">
            <h2>Configuration</h2>
            <div class="description">
                <p>Use these tools to configure and switch between database environments.</p>
            </div>
            <a href="db_config_switcher.php" class="tool-link">Database Environment Switcher</a>
        </div>

        <div class="tool-section">
            <h2>Cloud Database</h2>
            <div class="description">
                <p>Configure and test connection to your cloud database.</p>
            </div>
            <a href="test_cloud_connection.php" class="tool-link">Cloud Database Connection</a>
            <a href="remote_access_guide.php" class="tool-link" style="background-color: #2196F3;">Remote Access Guide</a>
        </div>

        <div class="tool-section">
            <h2>Testing</h2>
            <div class="description">
                <p>Use these tools to test your database connection and view database contents.</p>
            </div>
            <a href="test_connection.php" class="tool-link">Test Database Connection</a>
        </div>

        <div class="tool-section">
            <h2>Import/Export</h2>
            <div class="description">
                <p>Use these tools to import or export database schema and data.</p>
            </div>
            <a href="import_schema.php" class="tool-link">Import Database Schema</a>
        </div>

        <div class="tool-section">
            <h2>External Tools</h2>
            <div class="description">
                <p>Links to external database management tools.</p>
            </div>
            <a href="http://localhost/phpmyadmin/" target="_blank" class="tool-link">phpMyAdmin</a>
        </div>
    </div>
</body>
</html>
