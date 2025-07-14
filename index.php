<?php
// Azure App Service entry point - forwards to public directory
// Handles both local development and Azure production deployment

error_log("ROOT INDEX.PHP: Processing request - " . ($_SERVER['REQUEST_URI'] ?? 'no URI'));

// Get the request URI
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';

// Handle debug.php requests specifically
if (strpos($request_uri, '/debug.php') !== false || $request_uri === '/debug.php') {
    error_log("ROOT INDEX.PHP: Serving debug.php");
    if (file_exists(__DIR__ . '/public/debug.php')) {
        require_once __DIR__ . '/public/debug.php';
        exit();
    }
}

// If requesting a static file that exists in public, serve it
$public_file = __DIR__ . '/public' . $request_uri;
if (file_exists($public_file) && is_file($public_file)) {
    error_log("ROOT INDEX.PHP: Serving static file - " . $public_file);
    $mime_type = mime_content_type($public_file);
    header('Content-Type: ' . $mime_type);
    readfile($public_file);
    exit();
}

// For all other requests, forward to public/index.php
error_log("ROOT INDEX.PHP: Forwarding to public/index.php");
require_once __DIR__ . '/public/index.php';
?>