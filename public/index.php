<?php
// Create debug log function for Azure troubleshooting
function debug_log($message, $data = null) {
    $log_entry = date('Y-m-d H:i:s') . ' - ' . $message;
    if ($data !== null) {
        $log_entry .= ' - ' . json_encode($data);
    }
    $log_entry .= "\n";
    
    // Log to multiple places for Azure debugging
    error_log($log_entry);
    file_put_contents(__DIR__ . '/../debug.log', $log_entry, FILE_APPEND | LOCK_EX);
}

debug_log('=== REQUEST START ===', [
    'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? 'not set',
    'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'] ?? 'not set',
    'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? 'not set',
    'POST_DATA' => $_POST,
    'RAW_INPUT' => file_get_contents('php://input')
]);

// Autoloader
require_once __DIR__ . '/../vendor/autoload.php';
debug_log('Autoloader loaded');

// Start session for authentication
session_start();
debug_log('Session started', ['session_id' => session_id()]);

// Load environment variables manually
if (file_exists(__DIR__ . '/../.env')) {
    debug_log('Loading .env file');
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
        }
    }
    debug_log('.env loaded', ['variables_count' => count($_ENV)]);
} else {
    debug_log('ERROR: .env file not found, loading Azure environment configuration', ['path' => __DIR__ . '/../.env']);
    // Load Azure environment configuration
    require_once __DIR__ . '/../azure-env.php';
    debug_log('Azure environment configuration loaded', ['variables_count' => count($_ENV)]);
}

// Check if this is an API request or a web request
$request_uri = $_SERVER['REQUEST_URI'];
$is_api_request = strpos($request_uri, '/api') === 0 ||
    strpos($request_uri, 'action=') !== false ||
    $_SERVER['REQUEST_METHOD'] !== 'GET';

debug_log('Request analysis', [
    'request_uri' => $request_uri,
    'is_api_request' => $is_api_request,
    'method' => $_SERVER['REQUEST_METHOD']
]);

// If it's a web request to the root, serve the index.html
if (!$is_api_request && ($request_uri === '/' || $request_uri === '/index.php')) {
    debug_log('Serving index.html for web request');
    // Serve index.html from public directory
    if (file_exists(__DIR__ . '/index.html')) {
        header('Content-Type: text/html');
        readfile(__DIR__ . '/index.html');
        exit();
    } else {
        debug_log('ERROR: index.html not found in public directory');
    }
}

// If it's requesting index.html directly, serve it from public directory
if (strpos($request_uri, '/index.html') !== false) {
    debug_log('Direct request for index.html');
    if (file_exists(__DIR__ . '/index.html')) {
        header('Content-Type: text/html');
        readfile(__DIR__ . '/index.html');
        exit();
    } else {
        debug_log('ERROR: index.html not found for direct request');
    }
}

// If it's requesting debug.php directly, serve it from public directory
if (strpos($request_uri, '/debug.php') !== false) {
    debug_log('Direct request for debug.php');
    if (file_exists(__DIR__ . '/debug.php')) {
        header('Content-Type: text/html');
        require_once __DIR__ . '/debug.php';
        exit();
    } else {
        debug_log('ERROR: debug.php not found for direct request');
    }
}

// Enable CORS for API requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    debug_log('OPTIONS request handled');
    http_response_code(200);
    exit();
}

// Set content type for API responses
header('Content-Type: application/json');
debug_log('Processing API request');

// Error reporting (environment-specific)
if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    debug_log('Production mode enabled');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    debug_log('Development mode enabled');
}

// Test MongoDB extension
if (extension_loaded('mongodb')) {
    debug_log('MongoDB extension loaded successfully');
} else {
    debug_log('ERROR: MongoDB extension NOT loaded');
}

// Include router for API requests
debug_log('Including API router');
require_once __DIR__ . '/../backend/routes/api.php';
debug_log('=== REQUEST END ===');