<?php

namespace App\Models;

use App\Database\SimpleConnection;

class SimpleTask
{
    private $db;

    public function __construct()
    {
        $this->db = SimpleConnection::getInstance();
    }

    public function create($taskData)
    {
        $result = $this->db->insertTask($taskData);

        // Return with expected key name
        return [
            'success' => $result['success'],
            'taskId' => $result['id'],
            'message' => $result['success'] ? 'Task created successfully' : 'Failed to create task'
        ];
    }

    public function getAll()
    {
        return $this->db->getAllTasks();
    }

    public function findByUserId($userId)
    {
        return $this->db->getUserTasks($userId);
    }

    public function update($taskId, $taskData)
    {
        return $this->db->updateTask($taskId, $taskData);
    }

    public function updateStatus($taskId, $status)
    {
        return $this->db->updateTaskStatus($taskId, $status);
    }

    public function delete($taskId)
    {
        return $this->db->deleteTask($taskId);
    }
}
