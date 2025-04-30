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
    <title>Remote Database Access Guide</title>
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
        h1, h2, h3 {
            color: #333;
        }
        h1 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .section {
            margin-bottom: 30px;
        }
        .note {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .warning {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        code {
            background-color: #f8f9fa;
            padding: 2px 5px;
            border-radius: 3px;
            font-family: monospace;
        }
        pre {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
            font-family: monospace;
        }
        ol, ul {
            padding-left: 20px;
        }
        li {
            margin-bottom: 10px;
        }
        .step {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .step-number {
            display: inline-block;
            width: 30px;
            height: 30px;
            background-color: #007bff;
            color: white;
            text-align: center;
            line-height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }
        img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px 0;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Remote Database Access Guide</h1>
        
        <div class="note">
            <strong>Note:</strong> Based on your hosting control panel, your database is currently set to be accessible only from within the server (host: 127.0.0.1). To connect from your local phpMyAdmin, you need to enable remote access.
        </div>
        
        <div class="section">
            <h2>Why Remote Access Is Needed</h2>
            <p>Your database host is currently set to <code>127.0.0.1</code> (localhost), which means:</p>
            <ul>
                <li>The database can only be accessed from within the server itself</li>
                <li>External connections (like from your local computer) are not allowed</li>
                <li>To connect from your local phpMyAdmin, you need to configure remote access</li>
            </ul>
        </div>
        
        <div class="section">
            <h2>Option 1: Enable Remote Access in Hosting Control Panel</h2>
            
            <div class="step">
                <span class="step-number">1</span>
                <strong>Log in to your hosting control panel</strong>
                <p>Access your hosting control panel at gudang.nugijourney.com or through your hosting provider's main site.</p>
            </div>
            
            <div class="step">
                <span class="step-number">2</span>
                <strong>Navigate to Database Settings</strong>
                <p>Go to the Databases section where you can manage your database users and permissions.</p>
            </div>
            
            <div class="step">
                <span class="step-number">3</span>
                <strong>Edit Database User</strong>
                <p>Find your database user (itemtrack) and look for options to edit the user or its permissions.</p>
            </div>
            
            <div class="step">
                <span class="step-number">4</span>
                <strong>Configure Remote Access</strong>
                <p>Look for settings related to "Remote Access", "Host", or "Allowed IP Addresses". You have two options:</p>
                <ul>
                    <li><strong>Allow from your IP only:</strong> More secure, enter your current IP address</li>
                    <li><strong>Allow from any IP:</strong> Less secure, use '%' or '0.0.0.0/0' as the allowed host</li>
                </ul>
            </div>
            
            <div class="step">
                <span class="step-number">5</span>
                <strong>Save Changes</strong>
                <p>Apply the changes and wait for them to take effect (may take a few minutes).</p>
            </div>
            
            <div class="warning">
                <strong>Security Warning:</strong> Allowing remote access from any IP address ('%') is a security risk. If possible, restrict access to your specific IP address only.
            </div>
        </div>
        
        <div class="section">
            <h2>Option 2: Use SSH Tunneling</h2>
            <p>If your hosting provider doesn't allow changing the database host settings, you can use SSH tunneling to securely connect to your database:</p>
            
            <div class="step">
                <span class="step-number">1</span>
                <strong>Verify SSH Access</strong>
                <p>Make sure you have SSH access to your server. Check your hosting control panel for SSH credentials.</p>
            </div>
            
            <div class="step">
                <span class="step-number">2</span>
                <strong>Create SSH Tunnel</strong>
                <p>Open a terminal or command prompt and run the following command:</p>
                <pre>ssh -L 3307:127.0.0.1:3306 gudang@145.79.11.48 -N</pre>
                <p>Replace <code>gudang@145.79.11.48</code> with your actual SSH username and server IP.</p>
            </div>
            
            <div class="step">
                <span class="step-number">3</span>
                <strong>Connect to Database Through Tunnel</strong>
                <p>In your local phpMyAdmin or database tool, connect using these settings:</p>
                <ul>
                    <li><strong>Host:</strong> 127.0.0.1</li>
                    <li><strong>Port:</strong> 3307 (the local port from the SSH tunnel)</li>
                    <li><strong>Username:</strong> itemtrack</li>
                    <li><strong>Password:</strong> Your database password</li>
                </ul>
            </div>
        </div>
        
        <div class="section">
            <h2>Option 3: Use phpMyAdmin on Your Hosting</h2>
            <p>If your hosting provider offers phpMyAdmin, you can use it directly without setting up remote access:</p>
            
            <div class="step">
                <span class="step-number">1</span>
                <strong>Access Hosting phpMyAdmin</strong>
                <p>Log in to your hosting control panel and look for the phpMyAdmin link or Database Management section.</p>
            </div>
            
            <div class="step">
                <span class="step-number">2</span>
                <strong>Log in to phpMyAdmin</strong>
                <p>Use your database credentials (username: itemtrack) to log in.</p>
            </div>
            
            <div class="step">
                <span class="step-number">3</span>
                <strong>Manage Your Database</strong>
                <p>You can now manage your database directly through the hosting provider's phpMyAdmin interface.</p>
            </div>
        </div>
        
        <div class="section">
            <h2>Testing Your Connection</h2>
            <p>After setting up remote access, you can test your connection using our cloud database connection tool:</p>
            <a href="test_cloud_connection.php" class="button">Test Cloud Database Connection</a>
        </div>
        
        <div class="section">
            <h2>Troubleshooting</h2>
            <h3>Connection Refused</h3>
            <ul>
                <li>Check if your hosting provider allows remote database connections</li>
                <li>Verify that you've correctly set up remote access permissions</li>
                <li>Check if there's a firewall blocking the connection</li>
            </ul>
            
            <h3>Access Denied</h3>
            <ul>
                <li>Verify your database username and password</li>
                <li>Check if the user has permissions to connect from your IP address</li>
            </ul>
            
            <h3>Timeout</h3>
            <ul>
                <li>Check your internet connection</li>
                <li>Verify that the database server is running</li>
                <li>Try using a VPN if your ISP is blocking the connection</li>
            </ul>
        </div>
        
        <div class="section">
            <h2>Need More Help?</h2>
            <p>If you're still having trouble connecting to your cloud database, check your hosting provider's documentation or contact their support team for assistance.</p>
        </div>
        
        <div>
            <a href="index.php" class="button" style="background-color: #6c757d;">Back to Database Tools</a>
        </div>
    </div>
</body>
</html>
