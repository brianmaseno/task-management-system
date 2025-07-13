<?php

namespace App\Controllers;

use App\Models\SimpleUser;
use App\Services\SessionService;

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new SimpleUser();
    }

    /**
     * Handle HTTP request to get all users (Admin only)
     */
    public function handleGetUsers()
    {
        SessionService::requireAdmin();

        $result = $this->getUsers();
        echo json_encode($result);
    }

    /**
     * Handle HTTP request to create user (Admin only)
     */
    public function handleCreateUser()
    {
        SessionService::requireAdmin();

        $input = json_decode(file_get_contents('php://input'), true);
        $result = $this->createUser($input);

        http_response_code($result['success'] ? 201 : 400);
        echo json_encode($result);
    }

    /**
     * Handle HTTP request to update user (Admin only)
     */
    public function handleUpdateUser()
    {
        SessionService::requireAdmin();

        $input = json_decode(file_get_contents('php://input'), true);
        $result = $this->updateUser($input);

        http_response_code($result['success'] ? 200 : 400);
        echo json_encode($result);
    }

    /**
     * Handle HTTP request to delete user (Admin only)
     */
    public function handleDeleteUser()
    {
        SessionService::requireAdmin();

        $input = json_decode(file_get_contents('php://input'), true);
        $result = $this->deleteUser($input);

        http_response_code($result['success'] ? 200 : 400);
        echo json_encode($result);
    }

    public function getUsers()
    {
        try {
            $users = $this->userModel->getAll();

            // Convert ObjectIds to strings and remove passwords
            $formattedUsers = [];
            foreach ($users as $user) {
                $formattedUser = [
                    '_id' => (string) $user->_id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role ?? 'user',
                    'created_at' => isset($user->created_at) ? $user->created_at->toDateTime()->format('Y-m-d H:i:s') : ''
                ];
                $formattedUsers[] = $formattedUser;
            }

            return [
                'success' => true,
                'users' => $formattedUsers
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to fetch users: ' . $e->getMessage()];
        }
    }

    public function createUser($data)
    {
        try {
            $name = $data['name'] ?? '';
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';
            $role = $data['role'] ?? 'user';

            if (empty($name) || empty($email) || empty($password)) {
                return ['success' => false, 'message' => 'Name, email and password are required'];
            }

            // Check if user already exists
            $existingUser = $this->userModel->findByEmail($email);
            if ($existingUser) {
                return ['success' => false, 'message' => 'Email already exists'];
            }

            $result = $this->userModel->create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => $role
            ]);

            if ($result['success']) {
                return [
                    'success' => true,
                    'message' => 'User created successfully',
                    'userId' => $result['id']
                ];
            } else {
                return ['success' => false, 'message' => 'Failed to create user'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to create user: ' . $e->getMessage()];
        }
    }

    public function updateUser($data)
    {
        try {
            $userId = $data['userId'] ?? '';
            $name = $data['name'] ?? '';
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';
            $role = $data['role'] ?? 'user';

            if (empty($userId) || empty($name) || empty($email)) {
                return ['success' => false, 'message' => 'User ID, name and email are required'];
            }

            $updateData = [
                'name' => $name,
                'email' => $email,
                'role' => $role
            ];

            if (!empty($password)) {
                $updateData['password'] = $password;
            }

            $result = $this->userModel->update($userId, $updateData);

            if ($result) {
                return ['success' => true, 'message' => 'User updated successfully'];
            } else {
                return ['success' => false, 'message' => 'No changes made'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to update user: ' . $e->getMessage()];
        }
    }

    public function deleteUser($data)
    {
        try {
            $userId = $data['userId'] ?? $data['id'] ?? '';

            if (empty($userId)) {
                return ['success' => false, 'message' => 'User ID is required'];
            }

            $result = $this->userModel->delete($userId);

            if ($result) {
                return ['success' => true, 'message' => 'User deleted successfully'];
            } else {
                return ['success' => false, 'message' => 'User not found'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to delete user: ' . $e->getMessage()];
        }
    }
}
