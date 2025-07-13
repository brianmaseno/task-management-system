<?php
// Complete system initialization and testing script
require_once 'vendor/autoload.php';
require_once 'backend/app/Database/SimpleConnection.php';
require_once 'backend/app/Models/SimpleUser.php';
require_once 'backend/app/Models/SimpleTask.php';
require_once 'backend/app/Services/EmailService.php';

require_once 'backend/app/Controllers/AuthController.php';

use App\Database\SimpleConnection;
use App\Models\SimpleUser;
use App\Models\SimpleTask;
use App\Services\EmailService;
use App\Controllers\AuthController;

echo "=== TaskMaster Pro Complete System Test ===\n";

try {
    // Test 1: Database Connection
    echo "\n1. Testing database connection...\n";
    $db = SimpleConnection::getInstance();
    echo "✓ Database connection successful\n";

    // Test 2: Initialize Admin User
    echo "\n2. Creating admin user...\n";
    $userModel = new SimpleUser();

    $adminData = [
        'name' => 'Admin User',
        'email' => 'admin@taskmaster.com',
        'password' => 'admin123',
        'role' => 'admin'
    ];

    $adminResult = $userModel->create($adminData);
    if ($adminResult['success']) {
        echo "✓ Admin user created with ID: " . $adminResult['userId'] . "\n";
        $adminId = $adminResult['userId'];
    } else {
        echo "⚠ Admin already exists or creation failed: " . $adminResult['message'] . "\n";
        $existingAdmin = $userModel->findByEmail('admin@taskmaster.com');
        if ($existingAdmin) {
            $adminId = (string) $existingAdmin['_id'];
            echo "✓ Using existing admin with ID: " . $adminId . "\n";
        } else {
            throw new Exception('Could not create or find admin user');
        }
    }

    // Test 3: Create Test Users
    echo "\n3. Creating test users...\n";
    $testUsers = [
        [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'user123',
            'role' => 'user'
        ],
        [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => 'user123',
            'role' => 'user'
        ]
    ];

    $userIds = [];
    foreach ($testUsers as $userData) {
        $userResult = $userModel->create($userData);
        if ($userResult['success']) {
            echo "✓ Created user: " . $userData['name'] . " (ID: " . $userResult['userId'] . ")\n";
            $userIds[] = $userResult['userId'];
        } else {
            $existingUser = $userModel->findByEmail($userData['email']);
            if ($existingUser) {
                $userId = (string) $existingUser['_id'];
                echo "⚠ User already exists: " . $userData['name'] . " (ID: " . $userId . ")\n";
                $userIds[] = $userId;
            }
        }
    }

    // Test 4: Create Sample Tasks
    echo "\n4. Creating sample tasks...\n";
    $taskModel = new SimpleTask();

    $sampleTasks = [
        [
            'title' => 'Complete Project Documentation',
            'description' => 'Create comprehensive documentation for the TaskMaster Pro system including user guides and API documentation.',
            'assignedTo' => $userIds[0] ?? $adminId,
            'deadline' => date('Y-m-d H:i:s', strtotime('+1 week')),
            'status' => 'Pending',
            'createdBy' => $adminId
        ],
        [
            'title' => 'Design UI/UX Improvements',
            'description' => 'Research and implement user experience improvements for the dashboard and task management interface.',
            'assignedTo' => $userIds[1] ?? $adminId,
            'deadline' => date('Y-m-d H:i:s', strtotime('+2 weeks')),
            'status' => 'In Progress',
            'createdBy' => $adminId
        ],
        [
            'title' => 'Database Optimization',
            'description' => 'Optimize database queries and implement indexing strategies for better performance.',
            'assignedTo' => $userIds[0] ?? $adminId,
            'deadline' => date('Y-m-d H:i:s', strtotime('+10 days')),
            'status' => 'Pending',
            'createdBy' => $adminId
        ]
    ];

    foreach ($sampleTasks as $taskData) {
        $taskResult = $taskModel->create($taskData);
        if ($taskResult['success']) {
            echo "✓ Created task: " . $taskData['title'] . " (ID: " . $taskResult['taskId'] . ")\n";
        } else {
            echo "✗ Failed to create task: " . $taskData['title'] . "\n";
        }
    }

    // Test 5: Verify Data Retrieval
    echo "\n5. Testing data retrieval...\n";

    $allUsers = $userModel->getAll();
    echo "✓ Total users in system: " . count($allUsers) . "\n";

    $allTasks = $taskModel->getAll();
    echo "✓ Total tasks in system: " . count($allTasks) . "\n";

    // Test 6: Authentication Test
    echo "\n6. Testing authentication...\n";
    $authController = new AuthController();
    $authResult = $authController->login([
        'email' => 'admin@taskmaster.com',
        'password' => 'admin123'
    ]);
    if ($authResult['success']) {
        echo "✓ Admin authentication successful\n";
    } else {
        echo "✗ Admin authentication failed: " . ($authResult['message'] ?? 'Unknown error') . "\n";
    }

    // Test 7: Email Service Check
    echo "\n7. Testing email service configuration...\n";
    $emailService = new EmailService();
    if (!empty($_ENV['MAIL_USERNAME']) && !empty($_ENV['MAIL_PASSWORD'])) {
        echo "✓ Email credentials configured\n";
        echo "  - SMTP Host: " . ($_ENV['MAIL_HOST'] ?? 'Not set') . "\n";
        echo "  - From Email: " . ($_ENV['MAIL_FROM_ADDRESS'] ?? 'Not set') . "\n";
    } else {
        echo "⚠ Email credentials not configured\n";
        echo "  To enable email notifications:\n";
        echo "  1. Update MAIL_USERNAME and MAIL_PASSWORD in .env\n";
        echo "  2. Use Gmail App Password (not regular password)\n";
        echo "  3. Restart the server\n";
    }

    echo "\n=== System Initialization Complete! ===\n";
    echo "\n🎉 TaskMaster Pro is ready to use!\n";
    echo "\n📋 Available Accounts:\n";
    echo "👨‍💼 Admin: admin@taskmaster.com / admin123\n";
    echo "👤 User 1: john@example.com / user123\n";
    echo "👤 User 2: jane@example.com / user123\n";

    echo "\n🌐 Access the application at: http://localhost:8000/app.html\n";
    echo "\n✨ Features Available:\n";
    echo "  ✓ User registration and authentication\n";
    echo "  ✓ Admin user management (add, edit, delete)\n";
    echo "  ✓ Task creation and assignment\n";
    echo "  ✓ Task status tracking (Pending, In Progress, Completed)\n";
    echo "  ✓ User task dashboard\n";
    echo "  ✓ Admin task management\n";
    echo "  ✓ System logging and monitoring\n";
    echo "  ✓ Email notifications (when configured)\n";
    echo "  ✓ Responsive UI with Bootstrap\n";

    echo "\n🔧 Admin Features:\n";
    echo "  - Full user CRUD operations\n";
    echo "  - Task assignment to multiple users\n";
    echo "  - System log access and management\n";
    echo "  - Complete task overview\n";

    echo "\n👤 User Features:\n";
    echo "  - View assigned tasks\n";
    echo "  - Update task status\n";
    echo "  - Dashboard with task statistics\n";
    echo "  - Email notifications for new assignments\n";

} catch (Exception $e) {
    echo "\n✗ System test failed: " . $e->getMessage() . "\n";
    echo "Error details: " . $e->getTraceAsString() . "\n";
    exit(1);
}
?>