<?php
// Autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Start session for authentication
session_start();

// Load environment variables manually
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
        }
    }
}

// Check if this is an API request or a web request
$request_uri = $_SERVER['REQUEST_URI'];
$is_api_request = strpos($request_uri, '/api') === 0 || 
                  strpos($request_uri, 'action=') !== false ||
                  $_SERVER['REQUEST_METHOD'] !== 'GET';

// If it's a web request to the root, serve the index.html
if (!$is_api_request && ($request_uri === '/' || $request_uri === '/index.php')) {
    // Serve index.html from public directory
    if (file_exists(__DIR__ . '/index.html')) {
        header('Content-Type: text/html');
        readfile(__DIR__ . '/index.html');
        exit();
    }
}

// If it's requesting index.html directly, serve it from public directory
if (strpos($request_uri, '/index.html') !== false) {
    if (file_exists(__DIR__ . '/index.html')) {
        header('Content-Type: text/html');
        readfile(__DIR__ . '/index.html');
        exit();
    }
}

// Enable CORS for API requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Set content type for API responses
header('Content-Type: application/json');

// Error reporting (environment-specific)
if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Include router for API requests
require_once __DIR__ . '/../backend/routes/api.php';