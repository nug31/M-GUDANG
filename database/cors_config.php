<?php
// CORS Configuration for Vercel deployment
// Include this file at the top of all your PHP API files

// Allow requests from Vercel domain and local development
$allowed_origins = [
    'https://m-gudang.vercel.app',  // Vercel default domain
    'http://localhost:5173',        // Local development
    'https://gudang.nugjourney.com' // Your Hostinger domain
];

// Get the origin header
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

// Check if the origin is allowed
if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}

// Allow the following headers and methods
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
