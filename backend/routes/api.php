<?php

// Debug logging function (same as in index.php)
function api_debug_log($message, $data = null) {
    $log_entry = date('Y-m-d H:i:s') . ' [API] - ' . $message;
    if ($data !== null) {
        $log_entry .= ' - ' . json_encode($data);
    }
    $log_entry .= "\n";
    
    error_log($log_entry);
    file_put_contents(__DIR__ . '/../../debug.log', $log_entry, FILE_APPEND | LOCK_EX);
}

api_debug_log('=== API ROUTER START ===');

require_once __DIR__ . '/../app/Database/Connection.php';
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/Task.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/UserController.php';
require_once __DIR__ . '/../app/Controllers/TaskController.php';
require_once __DIR__ . '/../app/Controllers/LogController.php';
require_once __DIR__ . '/../app/Services/EmailService.php';
require_once __DIR__ . '/../app/Services/SessionService.php';
require_once __DIR__ . '/../app/Services/Logger.php';

api_debug_log('All includes loaded successfully');

use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\TaskController;
use App\Controllers\LogController;
use App\Services\SessionService;
use App\Services\Logger;

api_debug_log('Use statements processed');

// Initialize logger
$logger = new Logger();
api_debug_log('Logger initialized');

// Get the request data
$raw_input = file_get_contents('php://input');
$input = json_decode($raw_input, true);
$action = $input['action'] ?? '';

api_debug_log('Request data parsed', [
    'raw_input' => $raw_input,
    'parsed_input' => $input,
    'action' => $action,
    'POST' => $_POST,
    'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'not set'
]);

// Log the incoming request
$logger->info("API Request", [
    'action' => $action,
    'method' => $_SERVER['REQUEST_METHOD'],
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    'user_id' => $_SESSION['user_id'] ?? null
]);

try {
    api_debug_log('Processing action', ['action' => $action]);
    
    switch ($action) {
        case 'login':
            api_debug_log('Processing login request', $input);
            $controller = new AuthController();
            api_debug_log('AuthController created');
            
            $result = $controller->login($input);
            api_debug_log('Login result', $result);
            
            if ($result['success']) {
                SessionService::login($result['user']);
                api_debug_log('Session created for user', ['user_id' => $result['user']['_id'] ?? 'unknown']);
            }
            echo json_encode($result);
            break;

        case 'register':
            $controller = new AuthController();
            echo json_encode($controller->register($input));
            break;

        case 'logout':
            SessionService::logout();
            echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
            break;

        case 'checkAuth':
            if (SessionService::isLoggedIn()) {
                $user = SessionService::getCurrentUser();
                echo json_encode([
                    'authenticated' => true,
                    'user' => $user
                ]);
            } else {
                echo json_encode(['authenticated' => false]);
            }
            break;

        case 'initAdmin':
            $controller = new AuthController();
            echo json_encode($controller->ensureAdminExists());
            break;

        case 'getUsers':
            SessionService::requireAdmin();
            $controller = new UserController();
            echo json_encode($controller->getUsers());
            break;

        case 'createUser':
            SessionService::requireAdmin();
            $controller = new UserController();
            echo json_encode($controller->createUser($input));
            break;

        case 'updateUser':
            SessionService::requireAdmin();
            $controller = new UserController();
            echo json_encode($controller->updateUser($input));
            break;

        case 'deleteUser':
            SessionService::requireAdmin();
            $controller = new UserController();
            echo json_encode($controller->deleteUser($input));
            break;

        case 'getUserTasks':
            SessionService::requireLogin();
            $controller = new TaskController();
            echo json_encode($controller->getUserTasks($input));
            break;

        case 'getAllTasks':
            SessionService::requireLogin();
            $controller = new TaskController();
            echo json_encode($controller->getAllTasks());
            break;

        case 'createTask':
            SessionService::requireLogin();
            $controller = new TaskController();
            echo json_encode($controller->createTask($input));
            break;

        case 'updateTask':
            SessionService::requireLogin();
            $controller = new TaskController();
            echo json_encode($controller->updateTask($input));
            break;

        case 'updateTaskStatus':
            SessionService::requireLogin();
            $controller = new TaskController();
            echo json_encode($controller->updateTaskStatus($input));
            break;

        case 'deleteTask':
            SessionService::requireLogin();
            $controller = new TaskController();
            echo json_encode($controller->deleteTask($input));
            break;

        case 'getLogs':
            $controller = new LogController();
            echo $controller->getLogs();
            break;

        case 'clearLogs':
            $controller = new LogController();
            echo $controller->clearLogs();
            break;

        case 'test':
            api_debug_log('Test endpoint called');
            echo json_encode([
                'success' => true,
                'message' => 'API connection successful',
                'timestamp' => date('Y-m-d H:i:s'),
                'server' => [
                    'PHP_VERSION' => PHP_VERSION,
                    'MongoDB_extension' => extension_loaded('mongodb') ? 'loaded' : 'not loaded',
                    'session_status' => session_status(),
                    'current_user' => SessionService::getCurrentUser()
                ]
            ]);
            break;

        case 'testDatabase':
            api_debug_log('Database test endpoint called');
            try {
                require_once __DIR__ . '/../app/Database/SimpleConnection.php';
                $db = new App\Database\SimpleConnection();
                
                // Test basic connection
                $users = $db->getAllUsers();
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Database connection successful',
                    'user_count' => count($users),
                    'mongodb_uri' => isset($_ENV['MONGODB_URI']) ? '[SET]' : '[NOT SET]',
                    'env_vars' => [
                        'DB_HOST' => $_ENV['DB_HOST'] ?? 'not set',
                        'DB_DATABASE' => $_ENV['DB_DATABASE'] ?? 'not set',
                        'MONGODB_URI' => isset($_ENV['MONGODB_URI']) ? '[SET]' : '[NOT SET]'
                    ]
                ]);
            } catch (Exception $e) {
                api_debug_log('Database test failed', ['error' => $e->getMessage()]);
                echo json_encode([
                    'success' => false,
                    'message' => 'Database connection failed: ' . $e->getMessage(),
                    'mongodb_extension' => extension_loaded('mongodb') ? 'loaded' : 'not loaded'
                ]);
            }
            break;

        default:
            api_debug_log('Invalid action attempted', ['action' => $action]);
            $logger->warning("Invalid action attempted", ['action' => $action]);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    api_debug_log('API Exception caught', [
        'action' => $action,
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
    
    $logger->error("API Error", [
        'action' => $action,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

api_debug_log('=== API ROUTER END ===');
