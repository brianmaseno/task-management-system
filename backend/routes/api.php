<?php

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

use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\TaskController;
use App\Controllers\LogController;
use App\Services\SessionService;
use App\Services\Logger;

// Initialize logger
$logger = new Logger();

// Get the request data
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

// Log the incoming request
$logger->info("API Request", [
    'action' => $action,
    'method' => $_SERVER['REQUEST_METHOD'],
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    'user_id' => $_SESSION['user_id'] ?? null
]);

try {
    switch ($action) {
        case 'login':
            $controller = new AuthController();
            $result = $controller->login($input);
            if ($result['success']) {
                SessionService::login($result['user']);
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

        default:
            $logger->warning("Invalid action attempted", ['action' => $action]);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    $logger->error("API Error", [
        'action' => $action,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
