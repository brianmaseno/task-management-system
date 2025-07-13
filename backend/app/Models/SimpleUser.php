<?php

namespace App\Models;

use App\Database\SimpleConnection;

class SimpleUser
{
    private $db;

    public function __construct()
    {
        $this->db = SimpleConnection::getInstance();
    }

    public function create($userData)
    {
        // Hash password before storing
        $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);

        $result = $this->db->insertUser($userData);
        
        // Return with expected key name
        return [
            'success' => $result['success'],
            'userId' => $result['id'],
            'message' => $result['success'] ? 'User created successfully' : 'Failed to create user'
        ];
    }

    public function findByEmail($email)
    {
        return $this->db->findUserByEmail($email);
    }

    public function findById($id)
    {
        return $this->db->findUserById($id);
    }

    public function getTasks($userId)
    {
        return $this->db->getUserTasks($userId);
    }

    public function updateTaskStatus($taskId, $status)
    {
        return $this->db->updateTaskStatus($taskId, $status);
    }

    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }

    public function getAll()
    {
        return $this->db->getAllUsers();
    }

    public function update($userId, $userData)
    {
        return $this->db->updateUser($userId, $userData);
    }

    public function delete($userId)
    {
        return $this->db->deleteUser($userId);
    }
}
