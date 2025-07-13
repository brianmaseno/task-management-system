<?php

namespace App\Controllers;

use App\Models\SimpleTask;
use App\Models\SimpleUser;
use App\Services\EmailService;

class TaskController
{
    private $taskModel;
    private $userModel;
    private $emailService;

    public function __construct()
    {
        $this->taskModel = new SimpleTask();
        $this->userModel = new SimpleUser();
        $this->emailService = new EmailService();
    }

    public function getUserTasks($data)
    {
        try {
            $userId = $data['userId'] ?? '';

            if (empty($userId)) {
                return ['success' => false, 'message' => 'User ID is required'];
            }

            $tasks = $this->userModel->getTasks($userId);

            // Convert tasks to proper format
            $formattedTasks = [];
            foreach ($tasks as $task) {
                $formattedTask = [
                    '_id' => (string) $task->_id,
                    'title' => $task->title ?? '',
                    'description' => $task->description ?? '',
                    'status' => $task->status ?? 'Pending',
                    'assigned_to' => (string) $task->assigned_to,
                    'deadline' => isset($task->deadline) ? $task->deadline->toDateTime()->format('Y-m-d H:i:s') : '',
                    'created_at' => isset($task->created_at) ? $task->created_at->toDateTime()->format('Y-m-d H:i:s') : '',
                    'updated_at' => isset($task->updated_at) ? $task->updated_at->toDateTime()->format('Y-m-d H:i:s') : ''
                ];
                $formattedTasks[] = $formattedTask;
            }

            return [
                'success' => true,
                'tasks' => $formattedTasks
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to fetch tasks: ' . $e->getMessage()];
        }
    }    public function getAllTasks()
    {
        try {
            $tasks = $this->taskModel->getAll();
            
            // Convert tasks to proper format
            $formattedTasks = [];
            foreach ($tasks as $task) {
                $formattedTask = [
                    '_id' => (string)$task->_id,
                    'title' => $task->title ?? '',
                    'description' => $task->description ?? '',
                    'status' => $task->status ?? 'Pending',
                    'assigned_to' => (string)$task->assigned_to,
                    'deadline' => isset($task->deadline) ? $task->deadline->toDateTime()->format('Y-m-d H:i:s') : '',
                    'created_at' => isset($task->created_at) ? $task->created_at->toDateTime()->format('Y-m-d H:i:s') : '',
                    'updated_at' => isset($task->updated_at) ? $task->updated_at->toDateTime()->format('Y-m-d H:i:s') : ''
                ];
                $formattedTasks[] = $formattedTask;
            }

            return [
                'success' => true,
                'tasks' => $formattedTasks
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to fetch tasks: ' . $e->getMessage()];
        }
    }

    public function createTask($data)
    {
        try {
            $title = $data['title'] ?? '';
            $description = $data['description'] ?? '';
            $assignedTo = $data['assignedTo'] ?? '';
            $deadline = $data['deadline'] ?? '';
            $status = $data['status'] ?? 'Pending';

            if (empty($title) || empty($description) || empty($assignedTo) || empty($deadline)) {
                return ['success' => false, 'message' => 'All fields are required'];
            }

            $result = $this->taskModel->create([
                'title' => $title,
                'description' => $description,
                'assigned_to' => $assignedTo,
                'deadline' => $deadline,
                'status' => $status
            ]);

            if ($result['success']) {
                // Send email notification
                $user = $this->userModel->findById($assignedTo);
                if ($user) {
                    $userArray = [
                        'name' => $user->name,
                        'email' => $user->email
                    ];
                    $this->emailService->sendTaskAssignmentEmail($userArray, [
                        'title' => $title,
                        'description' => $description,
                        'deadline' => $deadline
                    ]);
                }

                return [
                    'success' => true,
                    'message' => 'Task created successfully',
                    'taskId' => $result['id']
                ];
            } else {
                return ['success' => false, 'message' => 'Failed to create task'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to create task: ' . $e->getMessage()];
        }
    }

    public function updateTask($data)
    {
        try {
            $taskId = $data['taskId'] ?? '';
            $title = $data['title'] ?? '';
            $description = $data['description'] ?? '';
            $assignedTo = $data['assignedTo'] ?? '';
            $deadline = $data['deadline'] ?? '';
            $status = $data['status'] ?? 'Pending';

            if (empty($taskId)) {
                return ['success' => false, 'message' => 'Task ID is required'];
            }

            $updateData = [
                'title' => $title,
                'description' => $description,
                'assignedTo' => $assignedTo,
                'deadline' => $deadline,
                'status' => $status
            ];

            $result = $this->taskModel->update($taskId, $updateData);

            if ($result) {
                return ['success' => true, 'message' => 'Task updated successfully'];
            } else {
                return ['success' => false, 'message' => 'No changes made'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to update task: ' . $e->getMessage()];
        }
    }

    public function updateTaskStatus($data)
    {
        try {
            $taskId = $data['taskId'] ?? '';
            $status = $data['status'] ?? '';

            if (empty($taskId) || empty($status)) {
                return ['success' => false, 'message' => 'Task ID and status are required'];
            }

            $result = $this->userModel->updateTaskStatus($taskId, $status);

            if ($result) {
                return ['success' => true, 'message' => 'Task status updated successfully'];
            } else {
                return ['success' => false, 'message' => 'No changes made'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to update task status: ' . $e->getMessage()];
        }
    }

    public function deleteTask($data)
    {
        try {
            $taskId = $data['taskId'] ?? '';

            if (empty($taskId)) {
                return ['success' => false, 'message' => 'Task ID is required'];
            }

            $result = $this->taskModel->delete($taskId);

            if ($result) {
                return ['success' => true, 'message' => 'Task deleted successfully'];
            } else {
                return ['success' => false, 'message' => 'Task not found'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to delete task: ' . $e->getMessage()];
        }
    }
}
