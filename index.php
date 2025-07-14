<?php
// Azure App Service entry point - forwards to public directory

// Get the request URI
$request_uri = $_SERVER['REQUEST_URI'];

// If requesting debug.php, serve it from public directory
if (strpos($request_uri, '/debug.php') !== false) {
    if (file_exists(__DIR__ . '/public/debug.php')) {
        header('Content-Type: text/html');
        require_once __DIR__ . '/public/debug.php';
        exit();
    }
}

// If requesting a static file that exists in public, serve it
$public_file = __DIR__ . '/public' . $request_uri;
if (file_exists($public_file) && is_file($public_file)) {
    $mime_type = mime_content_type($public_file);
    header('Content-Type: ' . $mime_type);
    readfile($public_file);
    exit();
}

// For all other requests, forward to public/index.php
require_once __DIR__ . '/public/index.php';
?>