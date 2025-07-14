<?php
// Azure PHP Router for built-in server
// This file handles all incoming requests and routes them to the appropriate handler

error_log("ROUTER: Request received - " . $_SERVER['REQUEST_URI'] . " (" . $_SERVER['REQUEST_METHOD'] . ")");

// Get the requested URI
$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

// Remove query string
$path = parse_url($request_uri, PHP_URL_PATH);

// Log the routing decision
error_log("ROUTER: Processing path - " . $path);

// Handle static files in public directory
$public_file = __DIR__ . '/public' . $path;

// Check if it's a static file that exists
if ($path !== '/' && $path !== '/index.php' && file_exists($public_file) && is_file($public_file)) {
    // Serve static files directly
    $extension = pathinfo($public_file, PATHINFO_EXTENSION);
    
    // Set appropriate content type
    switch($extension) {
        case 'css':
            header('Content-Type: text/css');
            break;
        case 'js':
            header('Content-Type: application/javascript');
            break;
        case 'png':
            header('Content-Type: image/png');
            break;
        case 'jpg':
        case 'jpeg':
            header('Content-Type: image/jpeg');
            break;
        case 'gif':
            header('Content-Type: image/gif');
            break;
        case 'svg':
            header('Content-Type: image/svg+xml');
            break;
        default:
            // Let PHP determine content type
            break;
    }
    
    error_log("ROUTER: Serving static file - " . $public_file);
    readfile($public_file);
    return;
}

// For all other requests, route to public/index.php
error_log("ROUTER: Routing to public/index.php");

// Change working directory to public
chdir(__DIR__ . '/public');

// Include the main application entry point
require_once __DIR__ . '/public/index.php';
?>
