<?php

namespace App\Controllers;

use App\Models\SimpleUser;
use App\Services\SessionService;
use App\Services\Logger;
use Exception;

class AuthController
{
    private $userModel;
    private $logger;

    public function __construct()
    {
        $this->userModel = new SimpleUser();
        $this->logger = new Logger();
    }

    /**
     * Handle HTTP login requests
     */
    public function handleLogin()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $result = $this->login($input);

            if ($result['success']) {
                // Start session for successful login
                SessionService::login($result['user']);
                http_response_code(200);
            } else {
                http_response_code(401);
            }
            echo json_encode($result);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Handle HTTP registration requests
     * Handle HTTP registration requests
     */
    public function handleRegister()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $result = $this->register($input);
            http_response_code($result['success'] ? 201 : 400);
            echo json_encode($result);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Handle logout
     * Handle logout
     */
    public function handleLogout()
    {
        SessionService::logout();
        echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
    }

    /**
     * Check authentication status
     */
    public function checkAuth()
    {
        if (SessionService::isLoggedIn()) {
            $user = SessionService::getCurrentUser();
            echo json_encode([
                'authenticated' => true,
                'user' => $user
            ]);
        } else {
            echo json_encode(['authenticated' => false]);
        }
    }

    /**
     * Ensure admin account exists - create if not found
     */
    public function ensureAdminExists()
    {
        try {
            // Check if admin already exists
            $adminUser = $this->userModel->findByEmail('admin@taskmaster.com');

            if (!$adminUser) {
                // Create admin account
                $adminData = [
                    'name' => 'Administrator',
                    'email' => 'admin@taskmaster.com',
                    'password' => 'admin123456',
                    'role' => 'admin'
                ];

                $result = $this->userModel->create($adminData);

                if ($result['success']) {
                    $this->logger->info('Admin account created successfully');
                    return ['success' => true, 'message' => 'Admin account created successfully'];
                } else {
                    $this->logger->error('Failed to create admin account');
                    return ['success' => false, 'message' => 'Failed to create admin account'];
                }
            } else {
                $this->logger->info('Admin account already exists');
                return ['success' => true, 'message' => 'Admin account already exists'];
            }
        } catch (Exception $e) {
            $this->logger->error('Error ensuring admin exists: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error ensuring admin exists: ' . $e->getMessage()];
        }
    }

    public function login($data)
    {
        try {
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';

            $this->logger->info("Login attempt", ['email' => $email]);

            if (empty($email) || empty($password)) {
                $this->logger->warning("Login failed - missing credentials", ['email' => $email]);
                return ['success' => false, 'message' => 'Email and password are required'];
            }

            $user = $this->userModel->findByEmail($email);

            if (!$user) {
                $this->logger->warning("Login failed - user not found", ['email' => $email]);
                return ['success' => false, 'message' => 'Invalid credentials'];
            }

            if (!$this->userModel->verifyPassword($password, $user->password)) {
                $this->logger->warning("Login failed - invalid password", ['email' => $email, 'user_id' => (string) $user->_id]);
                return ['success' => false, 'message' => 'Invalid credentials'];
            }

            // Convert user object to array and remove password
            $userArray = [
                '_id' => (string) $user->_id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'user',
                'created_at' => $user->created_at ?? null
            ];

            $this->logger->info("Login successful", ['email' => $email, 'user_id' => $userArray['_id'], 'role' => $userArray['role']]);

            return [
                'success' => true,
                'message' => 'Login successful',
                'user' => $userArray
            ];
        } catch (Exception $e) {
            $this->logger->error("Login error", ['email' => $email ?? 'unknown', 'error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Login failed: ' . $e->getMessage()];
        }
    }

    public function register($data)
    {
        try {
            $name = $data['name'] ?? '';
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';
            $role = $data['role'] ?? 'user';

            $this->logger->info("Registration attempt", ['email' => $email, 'name' => $name, 'role' => $role]);

            if (empty($name) || empty($email) || empty($password)) {
                $this->logger->warning("Registration failed - missing fields", ['email' => $email, 'name' => $name]);
                return ['success' => false, 'message' => 'Name, email and password are required'];
            }

            // Validate password strength
            if (strlen($password) < 6) {
                $this->logger->warning("Registration failed - weak password", ['email' => $email]);
                return ['success' => false, 'message' => 'Password must be at least 6 characters'];
            }

            // Check if user already exists
            $existingUser = $this->userModel->findByEmail($email);
            if ($existingUser) {
                $this->logger->warning("Registration failed - email exists", ['email' => $email]);
                return ['success' => false, 'message' => 'Email already exists'];
            }

            $result = $this->userModel->create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => $role
            ]);

            if ($result['success']) {
                $this->logger->info("Registration successful", ['email' => $email, 'user_id' => $result['id'], 'role' => $role]);

                return [
                    'success' => true,
                    'message' => 'User registered successfully',
                    'userId' => $result['id']
                ];
            } else {
                $this->logger->error("Registration failed - database error", ['email' => $email]);
                return ['success' => false, 'message' => 'Registration failed'];
            }
        } catch (\Exception $e) {
            $this->logger->error("Registration error", ['email' => $email ?? 'unknown', 'error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()];
        }
    }
}
