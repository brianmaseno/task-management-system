#!/usr/bin/env php
<?php
/**
 * TaskMaster Pro - Initialization Script
 * This script ensures the admin account exists and the database is properly set up.
 */

// Autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
        }
    }
}

// Initialize controllers
require_once __DIR__ . '/backend/app/Database/Connection.php';
require_once __DIR__ . '/backend/app/Models/User.php';
require_once __DIR__ . '/backend/app/Controllers/AuthController.php';
require_once __DIR__ . '/backend/app/Services/Logger.php';

use App\Controllers\AuthController;

echo "🚀 TaskMaster Pro - Initialization\n";
echo "==================================\n\n";

try {
    // Test database connection
    echo "📊 Testing database connection...\n";
    $connection = \App\Database\Connection::getInstance();
    if ($connection) {
        echo "✅ Database connection successful!\n\n";
    } else {
        throw new Exception("Failed to connect to database");
    }

    // Initialize admin account
    echo "👨‍💼 Ensuring admin account exists...\n";
    $authController = new AuthController();
    $result = $authController->ensureAdminExists();

    if ($result['success']) {
        echo "✅ " . $result['message'] . "\n\n";
    } else {
        throw new Exception($result['message']);
    }

    // Show admin credentials
    echo "🔐 Admin Credentials:\n";
    echo "Email: admin@taskmaster.com\n";
    echo "Password: admin123456\n\n";

    echo "🌐 Application URLs:\n";
    echo "Frontend: http://localhost:8000/app.html\n";
    echo "API: http://localhost:8000/index.php\n\n";

    echo "✅ Initialization completed successfully!\n";
    echo "You can now start the server with: php -S localhost:8000 -t public\n";

} catch (Exception $e) {
    echo "❌ Initialization failed: " . $e->getMessage() . "\n";
    echo "Please check your .env file and ensure MongoDB is accessible.\n";
    exit(1);
}
